controlItem = {

    deleteItem: function(itemName){
        swal({
            title: 'Are you sure?',
            text: 'You will not be able to recover this!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it',
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            buttonsStyling: false
        }).then(function() {
            swal({
                title: 'Deleted!',
                text: itemName + ' deleted.',
                type: 'success',
                confirmButtonClass: "btn btn-success",
                buttonsStyling: false
            }).catch(swal.noop);

        }, function(dismiss) {
            // dismiss can be 'overlay', 'cancel', 'close', 'esc', 'timer'
            // if (dismiss === 'cancel') {
            //     swal({
            //         title: 'Cancelled',
            //         text: itemName + ' safe :)',
            //         type: 'error',
            //         confirmButtonClass: "btn btn-info",
            //         buttonsStyling: false
            //     }).catch(swal.noop)
            // }
        });
    },




};
