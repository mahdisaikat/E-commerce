<?php

namespace App\DataTables;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TagsDataTable extends DataTable
{
    protected $title;
    protected $indexRoute;
    protected $model;
    protected $tag_type;

    public function __construct(Tag $model)
    {
        $this->model = $model;
        $this->title = trans('backend.tag');
        $this->indexRoute = 'tags';
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('serial', function ($query) {
                static $count = 1;
                $start = request()->get('start', 0);
                return $start + $count++;
            })
            ->editColumn('slug', function ($query) {
                return $query->slug ?? '-';
            })
            ->editColumn('type', function ($query) {
                return $query->type?->label() ?? 'Unknown';
            })
            ->editColumn('status', function ($query) {
                return $query->status == 1
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-warning">Inactive</span>';
            })
            ->addColumn('action', function ($query) {
                $action = '';
                if (auth()->user()->can($this->indexRoute . '.show')) {
                    $action .= '<button type="button" class="btn btn-outline-primary btn-sm" data-coreui-toggle="modal" data-coreui-target="#viewModal" title="View ' . $this->title . '" onclick="view(' . $query->id . ')"><i class="fas fa-eye"></i></button> ';
                }
                if (auth()->user()->can($this->indexRoute . '.edit')) {
                    $action .= '<button type="button" class="btn btn-outline-info btn-sm" data-coreui-toggle="modal" data-coreui-target="#editModal" title="Edit ' . $this->title . '" onclick="edit(' . $query->id . ')"><i class="fas fa-edit"></i></button> ';
                }
                if (auth()->user()->can($this->indexRoute . '.status')) {
                    if ($query->status == 1) {
                        $action .= '<button type="button" class="btn btn-sm btn-outline-warning" onclick="confirmStatus(' . $query->id . ', \'' . $this->title . '\',0)" data-toggle="tooltip" data-placement="top" title="Inactivate this ' . $this->title . ' status ??"><i class="fa-solid fa-circle-xmark"></i></button> ';
                    } else {
                        $action .= '<button type="button" class="btn btn-sm btn-outline-success" onclick="confirmStatus(' . $query->id . ', \'' . $this->title . '\',1)" data-toggle="tooltip" data-placement="top" title="Activate this ' . $this->title . ' status ??"><i class="fa-solid fa-circle-check"></i></button> ';
                    }
                }
                if (auth()->user()->can($this->indexRoute . '.destroy') && !$this->tag_type) {
                    $action .= '<button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete(' . $query->id . ', \'' . $this->title . '\')" data-toggle="tooltip" data-placement="top" title="Delete ' . $this->title . ' ??"><i class="fa-solid fa-trash-can"></i></button> ';
                }
                return $action;
            })
            ->rawColumns(['status', 'type', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                if ($k === 'tag_type') {
                    $this->tag_type = $v;
                }
            }
        } elseif ($key === 'tag_type') {
            $this->tag_type = $value;
        }

        return $this;
    }


    public function query(): QueryBuilder
    {
        $query = $this->model->newQuery();

        if ($this->tag_type) {
            $query->where('type', $this->tag_type);
        }

        return $query;
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        if (auth()->user()->can($this->indexRoute . '.create')) {
            $buttons[] = Button::make([
                'action' => 'function ( e, dt, button, config ) {
                    window.location.href = "javascript:void(0)";
                }'
            ])
                ->text('<span><i class="fa-solid fa-circle-plus"></i> Add</span>')
                ->attr([
                    'class' => 'btn btn-secondary buttons-add btn-success',
                    'data-coreui-toggle' => "modal",
                    'data-coreui-target' => "#addModal"
                ]);
        }
        $buttons[] = [
            Button::make('excel'),
            Button::make('csv'),
            // Button::make('pdf'),
            Button::make('print'),
            Button::make('reset'),
            Button::make('reload'),
        ];
        return $this->builder()
            ->setTableId($this->title . '-datatable')
            ->columns($this->getColumns())
            ->orderBy(1, 'asc')
            ->buttons($buttons);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::computed('serial')->title(trans('backend.serial_number')),
            Column::make('name')->title(trans('backend.name')),
        ];

        if (!$this->tag_type) {
            $columns[] = Column::make('type')->title(trans('backend.tag_type'));
        }

        $columns[] = Column::make('slug')->title(trans('backend.slug'));
        $columns[] = Column::make('status')->title(trans('backend.status'));

        // Add "action" column if the user has any relevant permissions
        if (
            auth()->user()->canany([
                $this->indexRoute . '.destroy',
                $this->indexRoute . '.edit',
                $this->indexRoute . '.status',
            ])
        ) {
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
        return $this->title . '_' . date('YmdHis');
    }
}
