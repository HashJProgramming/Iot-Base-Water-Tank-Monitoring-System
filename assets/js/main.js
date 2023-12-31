function rangehigh(data) {
    $(".rangehigh").html(data);
}

function rangelow(data) {
    $(".rangelow").html(data);
}

$(document).ready(function() {
        const currentPath = window.location.pathname;
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        const message = urlParams.get('message');

    $('#dataTable').DataTable( {
        // dom: 'Blfrtip',
        dom: 'Bfrtip',
        buttons: [
            
            { 
                extend: 'excel', 
                title: 'WTMS - IoT-Base Water Tank Monitoring System', 
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-excel"></i> EXCEL'
            },
            {
                extend: 'pdf',
                title: 'WTMS - IoT-Base Water Tank Monitoring System', 
                className: 'btn btn-primary',
                text: '<i class="fa fa-file-pdf"></i> PDF'
            },
            { 
                extend: 'print', 
                className: 'btn btn-primary',
                text: '<i class="fa fa-print"></i> Print',
                title: 'WTMS - IoT-Base Water Tank Monitoring System', 
                autoPrint: false,
                exportOptions: {
                    columns: ':visible',
                },
                customize: function (win) {
                    $(win.document.body).find('table').addClass('display').css('font-size', '9px');
                    $(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                        $(this).css('background-color','#D0D0D0');
                    });
                    $(win.document.body).find('h1').css('text-align','center');
                }
            }
        ]
    } );

    VANTA.WAVES({
        el: "#bg-animation",
        mouseControls: false,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0xaebee3,
        waveSpeed: 1.00,
        zoom: 0.60
      })  

        if (type == 'success') {
            Swal.fire(
                'Success!',
                 message,
                'success'
              )
        } else if (type == 'error') {
            Swal.fire(
                'Error!',
                 message,
                'error'
              )
        }

        setInterval( function() {
        var hours = new Date().getHours();
        $(".hours").html(( hours < 10 ? "0" : "" ) + hours);
        }, 1000);
    
        setInterval( function() {
        var minutes = new Date().getMinutes();
        $(".min").html(( minutes < 10 ? "0" : "" ) + minutes);
        },1000);
    
        setInterval( function() {
        var seconds = new Date().getSeconds();
        $(".sec").html(( seconds < 10 ? "0" : "" ) + seconds);
    
        var ampm = new Date().getHours() >= 12 ? 'pm' : 'am';
        $(".ampm").html(ampm.toUpperCase());
        },1000);

        
    var lastAlertTime = 0;
    setInterval( function() {
        $.ajax({
            url: 'api/stats',  
            dataType: 'json',
            success: function(data) {
                $('.water-level').html(data['level'] + "%");
                $('.water-liters').html(data['liters'] + "L");
                $(".distance").html(data['distance']);
                const level = data['level'];
                var currentTime = Date.now();
                
                if (currentTime - lastAlertTime >= 180000) {
                    if (level < 0) {
                        Swal.fire(
                            'Critical Low Water Level!',
                            'Please refill your water tank.',
                            'warning'
                        );
                        lastAlertTime = currentTime; 
                    } 
                    if (level > 100) {
                        Swal.fire(
                            'Critical High Water Level!',
                            'Please stop filling your water tank.',
                            'warning'
                        );
                        lastAlertTime = currentTime;
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
        },1500);

    fetch('api/check_sensor')
    .then(response => response.json()) 
    .then(data => {
        $(".sensor-status").html(data.status);
        if(data.status == "Running"){
            $(".restart-btn").html("Restart");
        } else{
            $(".restart-btn").html("Start");
        }
    })
    .catch(error => console.error('Error:', error));


} );