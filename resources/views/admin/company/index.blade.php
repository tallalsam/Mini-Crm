@extends('admin.layouts.app')
@section('title',$title)
@section('user_name',$user->name)

@section('content')
<div class="content-wrapper">
   <div class="row">
      <div class="col-lg-12 stretch-card">

         <div class="card">
            <div class="card-body">
            @if (\Session::has('success'))
                  <div class="alert alert-success">
                     {!! \Session::get('success') !!}
                  </div>
                @endif
                @if (\Session::has('error'))
                  <div class="alert alert-danger">
                     {!! \Session::get('error') !!}
                  </div>
                @endif
               <h4 class="card-title">{{$title}}</h4>
               <a class="nav-link add_button" href="{{route('companies.create')}}">
                <i class=" icon-plus menu-icon"></i>
                <span class="menu-title">{{ trans('sentence.add') }}</span>
              </a>
               <div class="table-responsive">
                  <table class="table  yajra-datatable">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>{{ trans('sentence.name') }}</th>
                           <th>{{ trans('sentence.email') }}</th>
                           <th>{{ trans('sentence.logo') }}</th>
                           <th>{{ trans('sentence.website') }}</th>
                           <th>{{ trans('sentence.action') }}</th>
                        </tr>
                     </thead>

                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div id="delmodal" class="modal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title">{{ trans('sentence.confirm_title') }}</h5>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
           </button>
       </div>
       <div class="modal-body">
       <p>{{ trans('sentence.confirm_msg') }}</p>
       <input type="hidden" name="id" id="confirmDelId" value="">

       </div>
       <div class="modal-footer">
       <button type="button" class="btn btn-danger" id="confirmDel">{{ trans('sentence.btn_del') }}</button>
       <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('sentence.btn_close') }}</button>
       </div>
       </div>
   </div>
</div>

@endsection


@section('footerScript')
@parent

<script type="text/javascript">

   $(document).ready(function(){
         var table = $('.yajra-datatable').DataTable({
               processing: true,
               serverSide: true,
               type:"GET",
               ajax: {
                  url: "{{ route('get-companies')}}",
                  //   data: function(data) {data.user_id = user_id, data.from_date = $('#from_date').val(), data.to_date = $('#to_date').val() } ,
                  method: 'GET',
               },
               columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'name', name: 'name'},
                  {data: 'email', name: 'email'},
                  {data: 'company_image', name: 'company_image'},
                  {data: 'website', name: 'website'},
                  {
                     data: 'action',
                     name: 'action',
                     orderable: true,
                     searchable: true
                  },
               ]
         });


         $(document).on("click",'.delete',function(){
               var id = $(this).attr('data-id');
               $('#confirmDelId').val(id);
               $('#delmodal').modal('show');
         });

         $(document).on("click",'#confirmDel',function(){
            var id = $('#confirmDelId').val();
            //do your own request an handle the results

            var project_url="{!! URL::to('/')!!}";// use base url

            $.ajax({
               method: "DELETE",
               type: "POST",
               url:'{{ route("companies.destroy", '') }}/'+id,
               data:{_token:'{{ csrf_token() }}'},
               success: function(data) {
                  if(data.status == 0){
                  }
                  else
                  {
                     $('#delmodal').modal('hide');
                     table.ajax.reload();
                  }
                }
            });

         });
   });
  </script>
  @endsection
