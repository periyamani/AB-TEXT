<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Add Health Test Structure
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Health Test Structure</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('vehicle_details_list')">
              <div class="card-body table-responsive p-0">
                  <form id="addHealth" @submit.prevent="createHealthTest()">
                  <div class="row">
                     <div class="form-group col-md-6">
                            <label>Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                         <div class="form-group col-md-6">
                            <label>VIN Number</label> <span class="text-danger">*</span>
                            <input v-model="form.vin_number" type="text" name="vin_number"  ref="vin_number" id="vin_number" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('vin_number') }">
                            <has-error :form="form" field="vin_number"></has-error>
                        </div>
                  </div>
<hr>

                 <div class="row">
                     <div class="form-group col-md-3">
                            <label>Category</label> <span class="text-danger">*</span>
                           
                        </div>
                         <div class="form-group col-md-3">
                            <label>Name</label> <span class="text-danger">*</span>
                            
                        </div>
                        <div class="form-group col-md-3">
                            <label>Type</label> <span class="text-danger">*</span>
                            
                        </div>
                        <div class="form-group col-md-3">
                           <button class="btn btn-primary btn-sm" type="button" id="" style="padding: 3px 8px 2px 8px;" title="Add Row" @click="addRow()"><i class="fa fa-plus"></i></button>
                        </div>
                  </div>
                  <div class="row fieldData" id="1" style="align-items: center;">
                     <div class="form-group col-md-3">
                           <input v-model="form.category" type="text" name="category[]"  ref="category" id="category1" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('category') }">
                            <has-error :form="form" field="category"></has-error>
                        </div>
                         <div class="form-group col-md-3">
                           <input v-model="form.field" type="text" name="field[]"  ref="field" id="field1" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('field') }">
                            <has-error :form="form" field="field"></has-error>
                        </div>
                        <div class="form-group col-md-3">
                           <select v-model="form.type" type="text" name="type[]"  ref="type" id="type1" required
                             
                                class="form-control" >
                                <option value="single" selected>Single Option</option>
                                <option value="multiple">Multiple Option</option>
                                <option value="input">Input Option</option>
                           </select>
                            
                        </div>
                        <div class="form-group col-md-3">
                           <button class="btn btn-danger btn-sm" @click="removeRow(1)" style="padding: 3px 8px 2px 8px;" type="button" title="Remove Row"><i class="fa fa-minus"></i></button>
                        </div>
                  </div>
                  <div id="addRow"></div>
                   <button type="button" @click="cancelBtn()" class="btn btn-rounded btn-primary-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="fa fa-arrow-left"></i> Back</button>
                   <button type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
                  </form>
              </div>
             
            </div>
             <div v-else>
                <h6 class="text-danger"> Permission Denied </h6>
            </div>
            <!-- /.card -->
          </div>
        </div>
    </div>
   
  </section>
</template>

<script>
    export default {
        data () {
            return {
                healthteststructures : {},
                form: new Form({
                    name: '',
                    vin_number: '',
                    category : [],
                    field : []
                }),
            }
        },
        methods: {
            addRow(){
                var last_id = $('.fieldData:last').attr('id');
                if(last_id){
                    var id = parseInt(last_id) + 1;
                    $('#addRow').append('<div class="row fieldData" id="'+id+'" style="align-items: center;"> '+
                    '<div class="form-group col-md-3"> <input v-model="form.category" type="text" name="category[]"  ref="category" id="category'+id+'" required class="form-control" :class="{ "is-invalid": form.errors.has("category") }"> <has-error :form="form" field="category"></has-error> </div>'+
                    '<div class="form-group col-md-3"> <input v-model="form.field" type="text" name="field[]"  ref="field" id="field'+id+'" required class="form-control" :class="{ "is-invalid": form.errors.has("field") }"> <has-error :form="form" field="field"></has-error> </div> '+
                    '<div class="form-group col-md-3"> <select v-model="form.type" type="text" name="type[]"  ref="type" id="type'+id+'" required class="form-control"> <option value="single">Single Option</option> <option value="multiple">Multiple Option</option> <option value="input">Input Option</option> </select> <has-error :form="form" field="type"></has-error> </div>'+
                    '<div class="form-group col-md-3"> <button class="btn btn-danger btn-sm" type="button" id="btn'+id+'" onclick="removeRow('+id+')" style="padding: 3px 8px 2px 8px;" title="Remove Row"><i class="fa fa-minus"></i></button> </div>'+
                    '</div>');
                    // $('#btn'+id).attr("click",this.removeRow(id))
                }else{
                     var id = parseInt(1);
                    $('#addRow').append('<div class="row fieldData" id="'+id+'" style="align-items: center;"> '+
                    '<div class="form-group col-md-3"> <input v-model="form.category" type="text" name="category[]"  ref="category" id="category'+id+'" required class="form-control" :class="{ "is-invalid": form.errors.has("category") }"> <has-error :form="form" field="category"></has-error> </div>'+
                    '<div class="form-group col-md-3"> <input v-model="form.field" type="text" name="field[]"  ref="field" id="field'+id+'" required class="form-control" :class="{ "is-invalid": form.errors.has("field") }"> <has-error :form="form" field="field"></has-error> </div> '+
                    '<div class="form-group col-md-3"> <select v-model="form.type" type="text" name="type[]"  ref="type" id="type'+id+'" required class="form-control"> <option value="single">Single Option</option> <option value="multiple">Multiple Option</option> <option value="input">Input Option</option> </select> <has-error :form="form" field="type"></has-error> </div>'+
                    '<div class="form-group col-md-3"> <button class="btn btn-danger btn-sm" type="button" style="padding: 3px 8px 2px 8px;" title="Remove Row" onclick="removeRow('+id+')"><i class="fa fa-minus"></i></button> </div>'+
                    '</div>');
                }
               
            },
             createHealthTest(){
              var data = $('#addHealth').serialize();
              axios.post('/api/addHealthTestStructure', data)
                    .then(function (response) {
                        if(response.data.message != "Already exist"){
                        // $("#ecu_id").val('').trigger('change');
                            Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                          window.location.href = '/healthteststructure';
                        
                        }else{
                            //  $("#ecu_id").val(ecudata).trigger('change')
                            Toast.fire({
                                icon: 'error',
                                title: response.data.message
                        });
                        }
                       
                    })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          },
          cancelBtn(){
                window.location.href = '/healthteststructure';
            },
            
        },
        mounted() {
            console.log('Component mounted.')
        },
        }
       
</script>