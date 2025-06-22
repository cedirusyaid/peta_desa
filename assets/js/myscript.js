new DataTable('#example', {
    columnDefs: [
        {
            targets: [2,3,4,5],
            className: 'noVis',
            visible:false
        }
    ],
    layout: {
        topStart: {
            buttons: ['excel', 'colvis']
        }
    }
});

$(document).ready(function() {
    $('#ralLevel0').on('change', function() {        
        const kd_ral_level_0 = $('#ralLevel0').val();
        if(kd_ral_level_0 != ''){
            $.ajax({
                url: base_url+'aplikasi/ambil_rallevel1',
                type: 'POST',
                dataType: 'text',
                data: {kd_ral_level_0: kd_ral_level_0},
                success: function(data){
                    $('#ralLevel1').html(data);
                }
            });
        }else{
            $('#ralLevel1').html('<option value="">-- Pilih Ral Level 1 --</option>');
        }
    });
});

$(document).ready(function() {
    $('#ralLevel0e').on('change', function() {        
        const kd_ral_level_0 = $('#ralLevel0e').val();
        if(kd_ral_level_0 != ''){
            $.ajax({
                url: base_url+'aplikasi/ambil_rallevel1',
                type: 'POST',
                dataType: 'text',
                data: {kd_ral_level_0: kd_ral_level_0},
                success: function(data){
                    $('#ralLevel1e').html(data);
                }
            });
        }else{
            $('#ralLevel1e').html('<option value="">-- Pilih Ral Level 1 --</option>');
        }
    });
});
