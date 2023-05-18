<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Live Parameters
                            <button v-if="$role.isPermission('add_live_data')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <button v-if="$role.isPermission('import_live_data')" type="button" class="btn btn-rounded btn-primary-rgba" @click="importCSV" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-import"></i> Import CSV</button>
                            <button v-if="$role.isPermission('export_live_data')" type="button" class="btn btn-rounded btn-info-rgba" @click="exportCSV(livelist)" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-export"></i> Export CSV</button>
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Live Parameters</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('live_data_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-bordered table-hover" style="width: 150%;">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Name</th>
                                            <th>DID</th>
                                            <th>Data size</th>
                                            <th>Unit</th>
                                            <th>Range</th>
                                            <th>Conversion Formula</th>
                                            <th>Conversion Method</th>
                                            <th>Category</th>
                                            <th>Vehicle Details</th>
                                            <th>ECU</th>
                                            <th>Icon</th>
                                            <th>Status</th>
                                            <th v-if="$role.isAction('update_live_data','delete_live_data')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="livedatas.data.length != 0">

<tr v-for="(livedata,index) in livedatas.data" :key="livedata.id">

                                       <td>{{fromData+index}}</td>
                                        <td >
                                            <p v-for="(nameData) in JSON.parse(livedata.name)" :key="nameData.id">
                                                {{nameData.name}} : {{nameData.value}}
                                            </p>
                                            
                                            </td>
                                         <td >{{livedata.did}}</td>
                                          <td >{{livedata.datasize}}</td>
                                           <td >{{livedata.unit}}</td>
                                            <td >{{livedata.range_from}} - {{livedata.range_to}}</td>
                                             <td >{{livedata.conversion_formula}}</td>
                                             <td >{{livedata.conversion_method}}</td>
                                              <td >{{livedata.category}}</td>
                                              <td >
                                            <div v-for="(veh,index) in livedata.vehicle_name" :key="veh.id">
                                                <span>{{index+1}} . {{veh.name}}</span>
                                            </div>
                                        </td> 
                                          <td >
                                            <div v-for="(ecu,index) in livedata.ecu_name" :key="ecu.id">
                                                <span>{{index+1}} . {{ecu.name}}</span>
                                            </div>
                                        </td>
                                        <td v-if="livedata.icon"><img v-bind:src="'../' + livedata.icon_path" v-bind:alt="pic" style="width: 60px;"></td>  
                                        <td v-else></td>
                                        <td v-if="livedata.active == 'Enabled'"><span style="color:green">Enabled</span></td>  
                                        <td v-if="livedata.active == 'Disabled'"><span style="color:red">Disabled</span></td>
                                        <td>

                                            <a v-if="$role.isPermission('update_live_data')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(livedata)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_live_data')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(livedata.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                            &nbsp;
                                             <div v-if="livedata.active == 'Enabled'">
                                                <a v-if="$role.isPermission('enable_disable_live_data')" href="#" class="btn btn-rounded btn-danger-rgba" @click="enableData(livedata.id,'Disable')">
                                                    Disable
                                                </a>
                                             </div>
                                             <div v-if="livedata.active == 'Disabled'">
                                                <a v-if="$role.isPermission('enable_disable_live_data')" href="#" class="btn btn-rounded btn-success-rgba" @click="enableData(livedata.id, 'Enable')">
                                                    Enable
                                                </a>
                                             </div>
                                            
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="livedatas.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="livedatas" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <div v-else>
                <h6 class="text-danger"> Permission Denied </h6>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Add Live Parameters</h5>
                    <h5 class="modal-title" v-show="editmode">Update Live Parameters</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateLiveData() : createLiveData()">
                    <div class="modal-body">
                      <div class="row" id="languageList">
                        <!-- <div class="form-group col-md-6">
                            <label>English</label> <span class="text-danger">*</span>
                            <input type="text" name="name"  ref="name" id='name' required class="form-control" >
                        </div> -->
                      </div>
                      <div class="row">
                        <div class="form-group col-md-6">
                            <label>DID</label> <span class="text-danger">*</span>
                            <input v-model="form.did" type="text" name="did"  ref="did" id="did" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('did') }">
                            <has-error :form="form" field="did"></has-error>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                            <label>Data Size</label> <span class="text-danger">*</span>
                            <input v-model="form.datasize" type="text" name="datasize"  ref="datasize" id="datasize" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('datasize') }">
                            <has-error :form="form" field="datasize"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Unit</label> 
                            <input v-model="form.unit" type="text" name="unit"  ref="unit" id="unit"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('unit') }">
                            <has-error :form="form" field="unit"></has-error>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                            <label>From Range</label> 
                            <input v-model="form.range_from" type="text" name="range_from"  ref="range_from" id="range_from"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('range_from') }">
                            <has-error :form="form" field="range_from"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>To Range</label>
                            <input v-model="form.range_to" type="text" name="range_to"  ref="range_to" id="range_to"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('range_to') }">
                            <has-error :form="form" field="range_to"></has-error>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                            <label>Conversion Formula</label> <span class="text-danger">*</span>
                            <input v-model="form.conversion_formula" type="text" name="conversion_formula"  ref="conversion_formula" id="conversion_formula" required
                                class="form-control" :class="{ 'is-invalid': form.errors.has('conversion') }">
                            <has-error :form="form" field="conversion"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Conversion Method</label> <span class="text-danger">*</span>
                            <input v-model="form.conversion_method" type="text" name="conversion_method"  ref="conversion_method" id="conversion_method" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('conversion_method') }">
                            <has-error :form="form" field="conversion_method"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                            <label>Category</label> <span class="text-danger">*</span>
                            <input v-model="form.category" type="text" name="category"  ref="category" id="category" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('category') }">
                            <has-error :form="form" field="category"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Vehicle Details</label> <span class="text-danger">*</span>
                            <select data-style="" class="selectpicker w-100" multiple="multiple" v-model="form.vehicle_mapping_id" name="vehicle_mapping_id[]" ref="vehicle_mapping_id" id="vehicle_mapping_id" required>
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in vehicles" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.vehicle_mapping_id">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="user_id"></has-error>
                        </div>

                        <div class="form-group">
                            <label>ECU</label> <span class="text-danger">*</span>
                            <select data-style="" class="selectpicker w-100" multiple="multiple"  v-model="form.ecu_id" name="ecu_id[]" id="ecu_id" ref="ecu_id" required >
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in ecu" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.ecu_type">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="ecu_id"></has-error>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6">
                            <label>Image</label>
                            <input type="file" name="icon" accept="image/*" ref="icon" id="icon" @change="imageShow"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('icon') }">
                            <has-error :form="form" field="icon"></has-error>
                        </div>

                         <div class="form-group col-md-6">
                            <label>Image Preview</label>
                            <div class="d-flex">
                            <div id="onImagePreview"></div>
                            <button v-if="showAddImageBtn" type="button" v-on:click="deleteAddImage" style="padding: 1px 0 0 11px; font-size: 15px; color: red;border: none; background: none;"><i class="fa fa-times" style="border: 1px solid; border-radius: 50%; padding: 2px 4px 2px 4px;cursor: pointer;"></i></button>
                            <button v-show="editmode" v-if="updateDeleteButton" type="button" class="sf" v-on:click="deleteUpdateMainImage" style="padding: 1px 0 0 11px; font-size: 15px; color: red;border: none; background: none;"><i class="fa fa-times" style="border: 1px solid; border-radius: 50%; padding: 2px 4px 2px 4px;cursor: pointer;"></i></button>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button  v-show="editmode" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Update</button>
                        <button  v-show="!editmode" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import CSV</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addimport" @submit.prevent="importCSVFile()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>CSV File</label> <span class="text-danger">*</span>
                            <input type="file" name="csv"  ref="csv" id="csv" required accept=".csv"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('csv') }">
                            <has-error :form="form" field="csv"></has-error>
                        </div>
                       
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Import</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                livedatas : {},
                showAddImageBtn : false,
                updateDeleteButton : false,
                vehicles : [],
                languages : [],
                 ecu : [],
                 livelist : [],
                fromData : "",
                form: new Form({
                    id : '',
                    name: '',
                    did: '',
                    datasize: '',
                    unit: '',
                    conversion_formula: '',
                    conversion_method: '',
                    range_from: '',
                    range_to: '',
                    category: '',
                    vehicle_mapping_id: [],
                     ecu_id : [],
                     icon: '',
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/livedata?page=' + page).then(({ data }) => (this.livedatas = data.data,this.fromData = data.data.from));

                   
            },
            updateLiveData(){
                 
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/livedata/post/'+this.form.id, data, config)
                .then(function (response) {
                    
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    
                })
                .catch(() => {
                    this.$Progress.fail();
                });
                    setTimeout(() => {
                        this.loadLiveData();
                    }, 800);
            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/livedataCheck/'+countriesData+'/'+name)
                .then((response) => {
                    if(response.data.data){
                      Toast.fire({
                      icon: 'error',
                      title: response.data.message
                    });
                    
                    }
                })
                } 
            },
            editModal(category){
                
                this.editmode = true;
                this.form.reset();
                this.form.fill(category);
                setTimeout(() => {
                    var parseFunction = JSON.parse(category.ecu_id);
                $("#ecu_id").val(parseFunction).trigger('change');
                var parseFunction1 = JSON.parse(category.vehicle_mapping_id);
                $("#vehicle_mapping_id").val(parseFunction1).trigger('change');
                if(category.icon_path){
                     $("#onImagePreview").empty();
                this.updateDeleteButton = true;
                 $("#onImagePreview").append("<img height='55px'/>");
                 $("#onImagePreview img").attr("src", '/'+category.icon_path);
                }else{
                     $("#onImagePreview").empty();
                    this.updateDeleteButton = false;
                }
                var nameData = JSON.parse(category.name);
                if(nameData){
                    $.each(nameData,function(key,value){
                        $("#"+value.id).val(value.value);
                    })
                }else{
                    $.each(this.languages,function(key,value){
                        $("#"+value.id).val('');
                    })
                }
                }, 100);
                 
                 
                $('#addNew').modal('show');
                
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $.each(this.languages,function(key,value){
                    $("#"+value.id).val('');
                })
                  setTimeout(() => {
                    $("#vehicle_mapping_id").val('').trigger('change');
                  $("#ecu_id").val('').trigger('change');
                }, 100);
                $('#addNew').modal('show');
               
            },
            deleteUpdateMainImage(){

                 this.form.post('/api/livedata/imageDelete/'+this.form.id).then((response)=>{
                      $("#onImagePreview").empty();
                    this.updateDeleteButton = false;
                           Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  }); 
                  setTimeout(() => {
                        this.loadLiveData();
                    }, 800);         
                 });
            },
            deleteData(id){
              Swal.fire({
                  title: 'Are you sure?',
                //   text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.delete('/api/livedata/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadLiveData();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
        imageShow(){
            $("#onImagePreview").html("");
                    var regex = /^([a-zA-Z0-9 \s_\\.\-:]())+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                    if ($('#icon').val().toLowerCase()) {
                        if ($.browser.msie && parseFloat(jQuery.browser.version) <= 9.0) {
                            $("#onImagePreview").show();
                            $("#onImagePreview")[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = $(this).val();
                        }
                        else {
                            if (typeof (FileReader) != "undefined") {
                                $("#onImagePreview").show();
                                $("#onImagePreview").append("<img height='55px'/>");
                                var reader = new FileReader();
                                this.showAddImageBtn = true;
                                reader.onload = function (e) {
                                    
                                    $("#onImagePreview img").attr("src", e.target.result);
                                    //  $("#onImagePreview").append('<button type="button" v-on:click="deleteAddImage" style="padding: 1px 0 0 11px; font-size: 23px; color: red;border: none; background: none;"><i class="fa fa-times" style="border: 1px solid; border-radius: 50%; padding: 2px 4px 2px 4px;cursor: pointer;"></i></button>')
                                }
                                reader.readAsDataURL($('#icon')[0].files[0]);
                                // imageData = $('#icon')[0].files[0];
                               
                            } else {
                                alert("This browser does not support FileReader.");
                            }
                            
                        }
                    } else {
                        alert("Please upload a valid image file.");
                    }
                },
                deleteAddImage: function(){
                    $("#onImagePreview").html("");
                    $('#icon').val('');
                    this.showAddImageBtn = false;
                },
          enableData(id,dataName){
              Swal.fire({
                  title: 'Are you sure?',
                //   text: "You won't be able to revert this!",
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, '+dataName+' it!'
                  }).then((result) => {

                      // Send request to the server
                        if (result.value) {
                              this.form.post('/api/livedata/enableData/'+id+'/'+dataName).then(()=>{
                                      Swal.fire(
                                      dataName+'d !',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadLiveData();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadLiveData(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/livedata").then(({ data }) => (this.livedatas = data.data,this.fromData = data.data.from));
                }
            },
            loadVehicle(){
              axios.get("/api/vehicledetail/list").then(({ data }) => (this.vehicles = data.data));
          },
          loadexport(){
              axios.get("/api/livedata/export").then(({ data }) => (this.livelist = data.data));
          },
           loadEciDetail(){
              axios.get("/api/ecidetail/list").then(({ data }) => (this.ecu = data.data));
          },
          loadLanguage(){
              axios.get("/api/language/list").then(({ data }) => (
                  this.languages = data.data,
                  this.listLang(data.data)
                  
                  ));
          },

            listLang(data){
                if(data.length > 0){
                    $.each(data,function(key,value){
                    $('#languageList').append('<div class="form-group col-md-6">'+
                            '<label>'+value.name+'</label> <span class="text-danger">*</span>'+
                            '<input type="text" name="name[]"  ref="'+value.id+'" id="'+value.id+'" required class="form-control" >'+
                        '</div>')
                    })
                }
            },
            createLiveData(){
                   const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                // var ecudata = $('#ecu_id').val();
                var data = new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/livedata/store', data, config)
                    .then(function (response) {
                  if(response.data.message != "Already exist"){
                        $('#addNew').modal('hide');
                         Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });
                  }else{
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
              setTimeout(() => {
                        this.loadLiveData();
                    }, 800);
            },
          importCSV(){
              $('#importModal').modal('show');
              $('#csv').val('');
          },
           importCSVFile(){
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data = new FormData($("#addimport")[0]);
                axios.post('/api/livedata/import',data, config)
                .then(function (response) {
                    console.log(response);
                    if(response.data.data == 'error'){
                      var errorContent = response.data.message.replace('Undefined index:', '');
                      var errorData = errorContent.toUpperCase()+' value is missing';

                      Toast.fire({
                      icon: 'error',
                      title: errorData
                    });
                    }else{
                    $('#importModal').modal('hide');
                    $('#csv').val('');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    }
                   
                })
                .catch(() => {
                    this.$Progress.fail();
                });
                    setTimeout(() => {
                        this.loadLiveData();
                    }, 800);
            },
             exportCSV(arrData){
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += [
                    Object.keys(arrData[0]).join(";"),
                    ...arrData.map(item => Object.values(item).join(";"))
                ]
                    .join("\n")
                    .replace(/(^\[)|(\]$)/gm, "");

                const data = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", data);
                link.setAttribute("download", "Live Parameters.csv");
                link.click();
                },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadLiveData();
            this.loadEciDetail();
            this.loadVehicle();
            this.loadexport();
            this.loadLanguage();
        }
    }
</script>
