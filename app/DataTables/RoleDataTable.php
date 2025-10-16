<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable {

    protected Role $model;
    protected string $title;
    protected string $indexRoute;

    public function __construct(Role $model)
    {
        $this->model = $model;
        $this->title = __('backend.role');
        $this->indexRoute = 'roles';
    }
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('serial_number', function ($query)
            {
                static $count = 1;
                $start = request()->get('start', 0);
                return $start + $count++;
            })
            ->editColumn('name', function ($query)
            {
                return $query->name ?? '';
            })
            ->editColumn('display_name', function ($query)
            {
                return $query->display_name ?? '';
            })
            ->addColumn('action', function ($query)
            {
                $a = '';
                if (auth()->user()->can($this->indexRoute . '.edit') && $query->id != auth()->user()->roles->pluck('id')[0])
                {
                    $a .= '<a href="' . route($this->indexRoute . '.edit', $query->id) . '" class="btn btn-outline-info btn-sm" title="Edit"><i class="fas fa-edit"></i></a> ';
                }
                if (auth()->user()->can($this->indexRoute . '.destroy') && $query->id != auth()->user()->roles->pluck('id')[0])
                {
                    $a .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $this->indexRoute . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $this->title . ' ??"><i class="fa-solid fa-trash-can"></i></button> ';
                }
                return $a;
            })
            ->rawColumns(['action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        if (auth()->user()->hasRole('systemadmin'))
        {
            return $model->newQuery()->whereNot('name', 'systemadmin');
        } else
        {
            return $model->newQuery()->whereNotIn('name', ['systemadmin']);
        }
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        if (auth()->user()->can($this->indexRoute . '.create'))
        {
            $buttons[] = Button::make('add')
                ->text('<i class="fa-solid fa-circle-plus"></i> ' . __('backend.add'));
        }
        $buttons[] = [
            Button::make('excel')->className('btn btn-primary'),
            Button::make('csv')->className('btn btn-info'),
            Button::make('pdf')->className('btn btn-danger'),
            Button::make('print')->className('btn btn-warning'),
            Button::make('reset')->className('btn btn-secondary'),
            Button::make('reload')->className('btn btn-dark')
        ];

        return $this->builder()
            ->setTableId("{$this->indexRoute}-datatable")
            ->columns($this->getColumns())
            ->orderBy(1, 'asc')
            ->minifiedAjax()
            ->parameters([
                'dom' => 'Bfrtip',
                'buttons' => $buttons,
                'responsive' => true,
                'language' => [
                    'search' => __('backend.search'),
                    'lengthMenu' => __('backend.show_entries'),
                ]
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('serial_number')->title(trans('backend.serial_number')),
            Column::make('name')->title(trans('backend.name')),
            Column::make('display_name')->title(trans('backend.display_name')),
        ];
        if (auth()->user()->can('roles.edit') || auth()->user()->can('roles.destroy'))
        {
            $columns[] = Column::computed('action')
                ->title(trans('backend.action'))
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center');
        }
        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return $this->title . 's_' . date('YmdHis');
    }
}
