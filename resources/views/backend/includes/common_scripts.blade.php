<script type="text/javascript">
    var toastTime = '{{ env('BACK_END_TOASTER_TIME', 5000) }}';

    function showToast(options) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: toastTime,
            timerProgressBar: true,
            showCloseButton: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
            ...options // Spread the options object to override any default settings
        });
    }

    function confirmStatus(id, name, status, route) {
        let table = $('#' + route + '-datatable').DataTable();
        var text = (status === 1) ? 'You want to activate this ' + name + ' status ??' :
            'You want to inactivate this ' + name + ' status ??';
        Swal.fire({
            title: 'Are you sure?',
            text: text,
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.href + '-status',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status,
                    },
                    success: function (response) {
                        if (response) {
                            showToast({
                                icon: response.type,
                                title: response.message
                            });
                            table.draw();
                        }
                    },
                    error: function (response) {
                        showToast({
                            icon: response.type,
                            title: response.message
                        });
                    }
                });
            }
        });
    }

    function confirmDelete(id, name, route) {
        let table = $('#' + route + '-datatable').DataTable();
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete this ' + name + ' ??',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes ! Delete',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.href + '/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        if (response) {
                            showToast({
                                icon: response.type,
                                title: response.message
                            });
                            table.draw();
                        }
                    },
                    error: function (error) {
                        showToast({
                            icon: 'error',
                            title: error.statusText
                        });
                    }
                });
            }
        });
    }

    $(document).ready(function () {

        // When Add Modal closes
        $('#addModal').on('hidden.coreui.modal', function () {
            let $form = $('#addForm');

            // Reset form & validation state
            $form.trigger("reset")
                .find('input, select, textarea')
                .removeClass('is-invalid');

            // Hide validation messages
            $form.find('.invalid-feedback').hide();
        });

        // When Edit Modal closes
        $('#editModal').on('hidden.coreui.modal', function () {
            let $form = $('#editForm');

            // Reset form & validation state
            $form.trigger("reset")
                .find('input, select, textarea')
                .removeClass('is-invalid');

            // Hide validation messages
            $form.find('.invalid-feedback').hide();
        });

    });


</script>

@if (\Session::has('success'))
<script type="text/javascript">
    $(document).ready(function () {
        const message = "{!! \Session::get('success') !!}";
        if (message) {
            $(function () {
                showToast({
                    icon: 'success',
                    title: message
                })
            });
        }
    });
</script>
@endif
@if (\Session::has('error'))
<script type="text/javascript">
    $(document).ready(function () {
        const message = "{!! \Session::get('error') !!}";
        if (message) {
            $(function () {
                showToast({
                    icon: 'error',
                    title: message
                })
            });
        }
    });
</script>
@endif