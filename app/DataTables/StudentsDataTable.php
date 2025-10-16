<?php

namespace App\DataTables;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class StudentsDataTable extends DataTable {
    protected Student $model;
    protected string $title;
    protected string $indexRoute;

    public function __construct(Student $model)
    {
        $this->model = $model;
        $this->title = __('backend.student');
        $this->indexRoute = 'students';
    }

    /**
     * Build the DataTable class.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('serial', fn($query) => $this->getSerialNumber($query))
            ->editColumn('student_id', fn($query) => $query->student_id ?? '-')
            ->editColumn('name_en', fn($query) => $query->name_en ?? '-')
            ->editColumn('name_bn', fn($query) => $query->name_bn ?? '-')
            ->editColumn('father_name', fn($query) => $query->father_name ?? '-')
            ->editColumn('mother_name', fn($query) => $query->mother_name ?? '-')
            ->editColumn('gender', fn($query) => $query->gender ?? '-')
            ->editColumn('class_applied', fn($query) => $query->class_applied ?? '-')
            ->editColumn('roll', fn($query) => $query->roll ?? '-')
            ->editColumn('shift', fn($query) => $query->shift ?? '-')
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

        // View Button (goes to show page)
        if ($authUser->can($this->indexRoute . '.show'))
        {
            $buttons .= '<a href="' . route($this->indexRoute . '.show', $data->id) . '" 
                        class="btn btn-outline-primary btn-sm" 
                        title="' . __('backend.view', ['title' => $this->title]) . '">
                        <i class="fas fa-eye"></i>
                     </a> ';
        }

        // Edit Button (goes to edit form page)
        if ($authUser->can($this->indexRoute . '.edit'))
        {
            $buttons .= '<a href="' . route($this->indexRoute . '.edit', $data->id) . '" 
                        class="btn btn-outline-info btn-sm" 
                        title="' . __('backend.edit', ['title' => $this->title]) . '">
                        <i class="fas fa-edit"></i>
                     </a> ';
        }

        // Delete Button (still confirm JS)
        if ($authUser->can($this->indexRoute . '.destroy'))
        {
            $buttons .= '<button type="button" 
                        class="btn btn-outline-danger btn-sm" 
                        onclick="confirmDelete(' . $data->id . ', \'' . $this->title . '\')" 
                        title="' . __('backend.delete', ['title' => $this->title]) . '">
                        <i class="fas fa-trash-can"></i>
                     </button> ';
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
        return $this->model->newQuery()->orderBy('name_en', 'asc');
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
                'pageLength' => 25,
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
                'text' => '<i class="fa-solid fa-circle-plus"></i> ' . __('backend.create_student'),
                'className' => 'btn btn-success',
                'action' => "function (e, dt, button, config) {
                    window.location.href = '" . route($this->indexRoute . '.create') . "';
                }"
            ];
        }

        if ($authUser && $authUser->can($this->indexRoute . '.import'))
        {
            $buttons[] = [
                'text' => '<i class="fa-solid fa-file-import"></i> ' . __('backend.import_students'),
                'titleAttr' => __('backend.import_students'),
                'className' => 'btn btn-primary',
                'attr' => [
                    'data-coreui-toggle' => 'modal',
                    'data-coreui-target' => '#importModal'
                ]
            ];
        }

        if ($authUser && $authUser->can($this->indexRoute . '.download-sample'))
        {
            $buttons[] = [
                'text' => '<i class="fas fa-download"></i> ' . __('backend.download_sample_excel_file'),
                'titleAttr' => __('backend.download_sample_excel_file'),
                'className' => 'btn btn-info',
                'action' => "function (e, dt, button, config) {
                    window.location.href = '" . route($this->indexRoute . '.download-sample') . "';
                }"
            ];
        }

        // Export buttons using proper DataTables format
        $buttons[] = [
            'extend' => 'excel',
            'className' => 'btn btn-primary',
            'text' => '<i class="fas fa-file-excel"></i> Excel'
        ];

        $buttons[] = [
            'extend' => 'csv',
            'className' => 'btn btn-info',
            'text' => '<i class="fas fa-file-csv"></i> CSV'
        ];

        $buttons[] = [
            'extend' => 'pdf',
            'className' => 'btn btn-danger',
            'text' => '<i class="fas fa-file-pdf"></i> PDF'
        ];

        return $buttons;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            // Column::computed('serial')->title(trans('backend.serial_number')),
            Column::make('student_id')->title(trans('backend.student_id')),
            Column::make('name_en')->title(trans('backend.name_en')),
            Column::make('name_bn')->title(trans('backend.name_bn')),
            Column::make('shift')->title(trans('backend.shift')),
            Column::make('roll')->title(trans('backend.roll')),
            Column::make('gender')->title(trans('backend.gender')),
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