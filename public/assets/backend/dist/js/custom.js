// Warn before remove data and redirect
function warnBeforeAction(URL, redirectURL) {
    swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Yes, proceed!",
            cancelButtonText: "No, cancel!",
            cancelButtonClass: "btn-danger",
            closeOnConfirm: false,
            closeOnCancel: false,
            showLoaderOnConfirm: true
        },
        function(isConfirm) {
            if (isConfirm) {
                setTimeout(function () {
                    $.ajax({
                        type: "GET",
                        url: URL,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(){
                            swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            window.location.href = redirectURL;
                        }
                    })
                }, 1000);

            } else {
                swal("Cancelled", "Action Cancelled :)", "error");
            }
        });
}
