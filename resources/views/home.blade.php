@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <div class="justify-content-between d-flex">
                        <h4>Yapılacak Notlarım</h4>
                        <button class="btn btn-success" data-toggle="modal" data-target="#addModal">Yeni Ekle</button>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                       
                            @if (Auth::user()->has_todo())

                            <div class="input-group mb-3">
                                <input id="search_text" type="text" class="form-control" placeholder="Kayıtlarda arama yap" >
                                <div class="input-group-append">
                                  <button id="search" class="btn btn-outline-secondary" type="button">Ara</button>
                                </div>
                              </div>
                            <div class="btn-group mb-3" role="group" >
                              
                                <a href="?latest" class="btn btn-light">Yeniden -> Eskiye</a>
                                <a href="?oldest" class="btn btn-light">Eskiden -> Yeniye</a>
                              </div>
                            <table class="table">
                                <thead>
                                    <th style="display:none">ID</th>
                                    <th>Kullanıcı</th>
                                    <th>Başlık</th>
                                    <th>Tarih</th>
                                    <th>Yapılacaklar</th>
                                    <th>Ayarlar</th>
                                </thead>
                                <tbody>
                                    
                                    @foreach ($todos as $todo)
                                    <tr>
                                        <td style="display: none;" class="todo-id">{{ $todo->id }}</td>
                                        <td class="todo-user">{{ $todo->user()->name }}</td>
                                        <td class="todo-title">{{ $todo->title }}</td>
                                        <td class="todo-date">{{ $todo->date }}</td>
                                        <td class="todo-note">{{ $todo->todo }}</td>
                                        <td>
                                            <div class="btn-group" role="group" >
                                                <button type="button" class="btn btn-warning edit-btn">Düzenle</button>
                                                <button type="button" class="btn btn-danger delete-btn">Kaldır</button>
                                              </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <h4>Listenizde henüz yapılacak bir not bulunmamaktadır.</h4>
                            @endif
                          
                        
                    </div>
                    <div class="card-footer">
                        {{ $todos->links() }}

                    </div>
                    
                </div>
            </div>
        </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >Not Ekle</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="error_save" style="display:none" class="alert alert-danger">
                <ul class="error-list">
    
                </ul>
              </div>
         <div class="form-group">
             <label for="title">Başlık</label>
             <input type="text" name="titile" id="title" class="form-control">
         </div>
         <div class="form-group">
            <label for="title">Not</label>
            <textarea class="form-control" rows="3" name="note" id="note"></textarea>
        </div>
        <div class="form-group">
            <label for="date">Tarih</label>
            <input type="date" id="date" class="form-control">
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Vazgeç</button>
          <button type="button" id="save_todo" class="btn btn-primary">Kaydet</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" >Notu Düzenle</h5>
          <div id="error_update" style="display:none" class="alert alert-danger">
            <ul class="error-list">

            </ul>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="title">Başlık</label>
                <input type="text" name="titile" id="title_e" class="form-control">
            </div>
            <div class="form-group">
               <label for="title">Not</label>
               <textarea class="form-control" rows="3" name="note_e" id="note_e"></textarea>
           </div>
           <div class="form-group">
               <label for="date">Tarih</label>
               <input type="date" id="date_e" class="form-control">
           </div>
        </div>
        <input type="hidden" id="note_id">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
          <button type="button" id="update_todo" class="btn btn-primary">Kaydet</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
<script>
        let csrf="{{csrf_token()}}";
	    let config_url= "{{ url('/') }}/";
        let = user = "{{ Auth::user()->name }}";
    jQuery(document).ready(function($){
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let current_tr;



            $('#search').on('click',function(){
                let text = $('#search_text').val();
                window.location = config_url+"home?search="+text;
            })


            $('#save_todo').on('click',function(){
                let data = {}
                data._token=csrf
                data.title = $('#title').val();
                data.date = $('#date').val();
                data.todo = $('#note').val();
                let error = $('#error_save');
                let ul =  error.find('ul');
                if(data.title==""){
                    error.show();
                    ul.append('<li>Başlık boş kalmamalı</li>')
                }else if(data.todo==""){
                    error.show();
                    ul.append('<li>Not boş kalmamalı</li>')
                }
                $.ajax({
                        url:config_url+"todo/store",
                        method:"post",
                        data:data,
                        dataType:"json",
                        success:function(res){
                            if(res.success==true || res.success=="true"){
                                $('#addModal').modal('hide');
                                error.hide();
                                $('#title').val("");
                                 $('#date').val("") ;
                                 $('#note').val("");
                                 let html = "<tr><td style='display: none;' class='todo-id'>"+res.id+"</td><td class='todo-user'>"+user+"</td><td class='todo-title'>"+res.title+"</td><td class='todo-date'>"+res.date+"</td><td class='todo-note'>"+res.todo+"</td>"
                                   html+= "<td><div class='btn-group' role='group' ><button type='button' class='btn btn-warning edit-btn'>Düzenle</button><button type='button' class='btn btn-danger delete-btn'>Kaldır</button></div></td>"
                                   $('.table tbody').append(html);
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Kaydınız Oluşturuldu',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                      }
                    })

            })

            $('#update_todo').on('click',function(){
                let data = {}
                data._token=csrf
                data.id = $('#note_id').val();
                data.title = $('#title_e').val();
                data.date = $('#date_e').val();
                data.todo = $('#note_e').val();

                let error = $('#error_update');
                let ul =  error.find('ul');
                if(data.title==""){
                    error.show();
                    ul.append('<li>Başlık boş kalmamalı</li>')
                }else if(data.todo==""){
                    error.show();
                    ul.append('<li>Not boş kalmamalı</li>')
                }
                $.ajax({
                        url:config_url+"todo/update",
                        method:"post",
                        data:data,
                        dataType:"json",
                        success:function(res){
                            if(res.success==true || res.success=="true"){
                                $('#editModal').modal('hide');
                                error.hide();
                                current_tr.find('.todo-title').text(data.title)
                                current_tr.find('.todo-date').text(res.date)
                                current_tr.find('.todo-note').text(data.todo)
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Kaydınız Güncellendi',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                      }
                    })

            })

            $('.table').on('click','.edit-btn',function(){
                let tr=$(this).closest("tr");
                let id=tr.find('.todo-id').text();
                let data = {}
                data._token=csrf
                data.id =id
                current_tr = tr;
                $.ajax({
                        url:config_url+"todo/edit",
                        method:"post",
                        data:data,
                        dataType:"json",
                        success:function(res){
                              $('#title_e').val(res.title);
                              $('#date_e').val(res.date);
                              $('#note_e').val(res.todo);
                              $('#note_id').val(res.id);
                              $('#editModal').modal('show');    
                      }
                     
                    })
            })
 

         $('.table').on('click','.delete-btn',function(){
            let tr=$(this).closest("tr");
            let id=tr.find('.todo-id').text();
            let data = {}
            data._token=csrf
            data.id =id
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu işlemi geri alamayabilirsiniz!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'İptal Et',
                confirmButtonText: 'Evet, Kaldır!',
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result)=>{
                if(result.value){
                    $.ajax({
                        url:config_url+"todo/delete",
                        method:"post",
                        data:data,
                        dataType:"json",
                        success:function(res){
                            if(res.success==true || res.success=="true"){
                            tr.remove();
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Kaydınız Kaldırıldı',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                      }
                    })
                }
            })

        })

    })
</script>
@endsection