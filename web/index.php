<?php



    include 'bootstrap.php';
    $Conn = new DatabaseConnection();
    $db = $Conn->connection();
    
    $auth = new \Delight\Auth\Auth($db);

    global $config;   
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $config['nombre_sitio']?></title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" >

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      
      .pull-right{
          float:right;
          clear: both;
      }
      .pull-left{
          float:left;
          clear: both;
      }
    </style>

    
  </head>
  <body>
      
      <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
          <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Examen Company</a>
      </header>
      <div class="container-fluid">
          <div class="row">
              <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                  <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                      <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="javascript:void(0);">
                         <button class="w-100 btn btn-lg btn-primary populate">AGREGAR REGISTROS</button>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);">
                         <button class="w-100 btn btn-lg btn-primary remove">ELIMINAR REGISTROS</button>
                        </a>
                      </li>
                      
                    </ul>
            
                  </div>
                </nav>
                
              <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                  <div class="table-responsive">
                    <table class="table table-striped table-sm">
                      <thead>
                        <tr>
                          <th scope="col">ID</th>
                          <th scope="col">FirstName</th>
                          <th scope="col">LastName</th>
                          <th scope="col">Phone</th>
                          <th scope="col">Address</th>
                          <th scope="col">City</th>
                          <th scope="col">State</th>
                          <th scope="col">Zip</th>
                          <th scope="col">Country</th>
                          <th scope="col">Email</th>
                          <th scope="col">Company</th>
                          <th scope="col">Date</th>
                        </tr>
                      </thead>
                      <tbody class="table-content">
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        
                      </tbody>
                    </table>
                        <div class="pagination2"></div>
                  </div>
               </main>
          </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
        <script>
        
            function loadPage(page){
                jQuery('.table-content').empty()
                jQuery.post('/api/customers/'+page+'/10', function(list){
                    
                    jQuery.each(list.results, function(i,j){
                        jQuery('.table-content').append('<tr><td>'+j.id+'</td> <td>'+j.FirstName+'</td> <td>'+j.LastName+'</td> <td>'+j.Phone+'</td> <td>'+j.Address+'</td> <td>'+j.City+'</td> <td>'+j.State+'</td> <td>'+j.Zip+'</td> <td>'+j.Country+'</td> <td>'+j.Email+'</td> <td>'+j.Company+'</td> <td>'+j.Date+'</td> </tr>')    
                    })
                    var pagination = '<nav aria-label="Page navigation example" class="pull-right"> <ul class="pagination">';
                    
                    if(list.page == 1){
                   
                        pagination += '<li class="page-item disabled"> <a class="page-link" href="#" aria-label="Previous" aria-disabled="true"> <span aria-hidden="true">&laquo;</span> </a> </li>';
                    }else{
                        pagination += '<li class="page-item"> <a class="page-link" href="#" aria-label="Previous"> <span aria-hidden="true">&laquo;</span> </a> </li>';
                    }
                    
                    
                    for(i=1; i<list.total_pages+1; i++){
                        pagination += ' <li class="page-item page-'+i+' '+((i==page)?'active':'')+' "><a class="page-link page-click" href="javascript:void(0);" onclick="loadPage('+i+');" data-page="'+i+'">'+i+'</a></li>';    
                    }
                    
                    if(list.page == list.total_pages){
                        pagination += ' <li class="page-item disabled"> <a class="page-link" href="#" aria-label="Next" aria-disabled="true"> <span aria-hidden="true">&raquo;</span> </a> </li>';    
                    }else{
                        pagination += ' <li class="page-item"> <a class="page-link" href="#" aria-label="Next"> <span aria-hidden="true">&raquo;</span> </a> </li>';
                    }
                    pagination += '  </ul> </nav> ';
                    
                    console.log(pagination)
                    jQuery('.page-'+page).addClass('active')
                    jQuery('.pagination2').html(pagination)
                })
            }
        
            jQuery(document).ready(function(){
                
                loadPage(1)
                
                
                jQuery('.page-click').click(function(){
                    var page = jQuery(this).attr('data-page');
                    
                    loadPage(page)
                    
                    
                })
                
                
                jQuery('.populate').click(function(){
                    jQuery.post('/api/faker/create/100/', function(data){
                        alert(data.message)
                        loadPage(1)
                    })
                })
                
                jQuery('.remove').click(function(){
                    jQuery.post('/api/faker/clear/', function(data){
                        alert(data.message)
                        loadPage(1)
                    })
                })
            })
        </script>
  </body>
</html>