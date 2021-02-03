$(function () {
    $('.js-basic-example').DataTable({
        responsive: true,
        language: {
           url: "//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Japanese.json"
       }
   });

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
        {
            extend: 'csv',
            text: 'Download List',
            className: 'btn btn-primary',
            exportOptions: {
                columns: ':not(.notexport)'
            }
        }],

    });
});


