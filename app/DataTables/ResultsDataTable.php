<?php

namespace App\DataTables;

use App\Models\Result;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class ResultsDataTable extends DataTable {
    protected Result $model;
    protected string $title;
    protected string $indexRoute;

    public function __construct(Result $model)
    {
        $this->model = $model;
        $this->title = __('backend.result');
        $this->indexRoute = 'results';
    }

    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('serial', fn($query) => $this->getSerialNumber($query))
            ->addColumn('student', fn($query) => $query->student?->name_en ?? '-')
            ->addColumn('exam', fn($query) => $query->exam?->name ?? '-')
            ->addColumn('subject', fn($query) => $query->subject?->name ?? '-')
            ->editColumn('marks', fn($query) => $query->marks ?? '-')
            ->editColumn('grade', fn($query) => $query->grade ?? '-')
            ->editColumn('status', function ($query)
            {
                return $query->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', fn($query) => $this->getActionButtons($query))
            ->rawColumns(['status', 'action'])
            ->setRowId('id')
            ->filterColumn('status', function ($query, $keyword)
            {
                if (strtolower($keyword) == "active")
                {
                    $query->where('status', 1);
                }
                if (strtolower($keyword) == "inactive")
                {
                    $query->where('status', 0);
                }
            });
    }

    protected function getSerialNumber($query): int
    {
        static $count = 1;
        return request()->get('start', 0) + $count++;
    }

    protected function getActionButtons($data): string
    {
        $buttons = '';
        $authUser = Auth::user();

        if (!$authUser)
        {
            return $buttons;
        }

        if ($authUser->can($this->indexRoute . '.show'))
        {
            $buttons .= $this->createButton(
                'primary',
                'eye',
                __('backend.view', ['title' => $this->title]),
                $data->id,
                'viewModal'
            );
        }

        if ($authUser->can($this->indexRoute . '.edit'))
        {
            $buttons .= $this->createButton(
                'info',
                'edit',
                __('backend.edit', ['title' => $this->title]),
                $data->id,
                'editModal'
            );
        }

        if ($authUser->can($this->indexRoute . '.destroy'))
        {
            $buttons .= $this->createButton(
                'danger',
                'trash-can',
                __('backend.delete', ['title' => $this->title]),
                $data->id,
                "confirmDelete($data->id, '$this->title')"
            );
        }

        return $buttons;
    }

    protected function createButton(
        string $color,
        string $icon,
        string $title,
        int $id,
        string $onclick
    ): string {
        if (in_array($onclick, ['viewModal', 'editModal']))
        {
            return sprintf(
                '<button type="button" class="btn btn-outline-%s btn-sm" data-coreui-toggle="modal"
                 data-coreui-target="#%s" title="%s" onclick="%s(%d)">
                    <i class="fas fa-%s"></i>
                </button> ',
                $color,
                $onclick,
                $title,
                str_replace('Modal', '', $onclick),
                $id,
                $icon
            );
        }

        return sprintf(
            '<button type="button" class="btn btn-outline-%s btn-sm" title="%s" onclick="%s">
                <i class="fas fa-%s"></i>
            </button> ',
            $color,
            $title,
            $onclick,
            $icon
        );
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder
    {
        return $this->model->newQuery();
    }

    /**
     * Configure the HTML builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId("{$this->indexRoute}-datatable")
            ->columns($this->getColumns())
            ->orderBy(1, 'asc')
            ->minifiedAjax()
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => $this->getTableButtons(),
                'responsive' => true,
                'language' => [
                    'search' => __('backend.search'),
                    'lengthMenu' => __('backend.show_entries'),
                ]
            ]);
    }

    protected function getTableButtons(): array
    {
        $buttons = [];
        $authUser = Auth::user();

        if ($authUser && $authUser->can($this->indexRoute . '.create'))
        {
            $buttons[] = [
                'text' => '<i class="fa-solid fa-circle-plus"></i> ' . __('backend.add'),
                'titleAttr' => __('backend.add'),
                'className' => 'btn btn-success',
                'attr' => [
                    'data-coreui-toggle' => 'modal',
                    'data-coreui-target' => '#addModal'
                ]
            ];
        }

        return array_merge($buttons, [
            Button::make('excel')->className('btn btn-primary'),
            Button::make('csv')->className('btn btn-info'),
            Button::make('pdf')->className('btn btn-danger'),
            Button::make('print')->className('btn btn-warning'),
            Button::make('reset')->className('btn btn-secondary'),
            Button::make('reload')->className('btn btn-dark')
        ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::computed('serial')->title(trans('backend.serial_number')),
            Column::make('exam')->title(trans('backend.exam')),
            Column::make('subject')->title(trans('backend.subject')),
            Column::make('student')->title(trans('backend.student')),
            Column::make('marks')->title(trans('backend.marks')),
            Column::make('grade')->title(trans('backend.grade')),
            Column::make('status')->title(trans('backend.status')),
        ];

        if ($this->shouldShowActionColumn())
        {
            $columns[] = Column::computed('action')
                ->title(trans('backend.action'))
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center');
        }

        return $columns;
    }

    protected function shouldShowActionColumn(): bool
    {
        $authUser = Auth::user();

        if (!$authUser)
        {
            return false;
        }

        return $authUser->canany([
            $this->indexRoute . '.show',
            $this->indexRoute . '.destroy',
            $this->indexRoute . '.edit',
        ]);
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return "{$this->title}_" . date('YmdHis');
    }
}