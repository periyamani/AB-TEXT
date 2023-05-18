<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Write Data
                            <button v-if="$role.isPermission('add_write_data')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
<button v-if="$role.isPermission('import_write_data')" type="button" class="btn btn-rounded btn-primary-rgba" @click="importCSV" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-import"></i> Import CSV</button>
<button v-if="$role.isPermission('import_write_data')" type="button" class="btn btn-rounded btn-info-rgba" @click="exportCSV(writelist)" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-export"></i> Export CSV</button>
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Write Data</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('write_data_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table table-bordered table-hover" style="width: 150%;">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Name</th>
                                            <th>DID</th>
                                            <th>Hint</th>
                                            <th>Range</th>
                                            <th>Conversion</th>
                                            <th>Default Value</th>
                                            <th>Vehicle Details</th>
                                            <th>ECU</th>
                                            <th>Security</th>
                                            <th>Method</th>
                                            <th v-if="$role.isAction('update_write_data','delete_write_data')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="writedatas.data.length != 0">

<tr v-for="(writedata,index) in writedatas.data" :key="writedata.id">

                                       <td>{{fromData+index}}</td>
                                        <td >
                                            <p v-for="(nameData) in JSON.parse(writedata.name)" :key="nameData.id">
                                                {{nameData.name}} : {{nameData.value}}
                                            </p>
                                            
                                            </td>
                                         <td >{{writedata.did}}</td>
                                         <td >{{writedata.hint}}</td>
                                         <td >{{writedata.range}}</td>
                                         <td >{{writedata.conversion}}</td>
                                         <td >{{writedata.default_value}}</td>
                                          <td >
                                            <div v-for="(veh,index) in writedata.vehicle_name" :key="veh.id">
                                                <span>{{index+1}} . {{veh.name}}</span>
                                            </div>
                                        </td> 
                                          <td >
                                            <div v-for="(ecu,index) in writedata.ecu_name" :key="ecu.id">
                                                <span>{{index+1}} . {{ecu.name}}</span>
                                            </div>
                                        </td>
                                         <td >{{writedata.security}}</td>
                                         <td >{{writedata.method}}</td>
                                        <td>

                                            <a v-if="$role.isPermission('update_write_data')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(writedata)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_write_data')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(writedata.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="writedatas.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="writedatas" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add Write Data</h5>
                    <h5 class="modal-title" v-show="editmode">Update Write Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateWriteData() : createWriteData()">
                    <div class="modal-body">
                     
                           <div class="row" id="languageList">
                        <!-- <div class="form-group col-md-6">
                            <label>English</label> <span class="text-danger">*</span>
                            <input type="text" name="name"  ref="name" id='name' required class="form-control" >
                        </div> -->
                           </div>
                         <div class="row">
                        <div class="form-group col-md-12">
                            <label>DID</label> <span class="text-danger">*</span>
                            <input v-model="form.did" type="text" name="did"  ref="did" id="did" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('did') }">
                            <has-error :form="form" field="did"></has-error>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                            <label>Hint</label> <span class="text-danger">*</span>
                            <input v-model="form.hint" type="text" name="hint"  ref="hint" id="hint" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('hint') }">
                            <has-error :form="form" field="hint"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Method</label> <span class="text-danger">*</span>
                            <input v-model="form.method" type="text" name="method"  ref="method" id="method" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('method') }">
                            <has-error :form="form" field="method"></has-error>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                            <label>Range</label> <span class="text-danger">*</span>
                            <input v-model="form.range" type="text" name="range"  ref="range" id="range" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('range') }">
                            <has-error :form="form" field="range"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Security</label> <span class="text-danger">*</span>
                            <input v-model="form.security" type="text" name="security"  ref="security" id="security" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('security') }">
                            <has-error :form="form" field="security"></has-error>
                        </div>
                      </div>
                      <div class="row">
                          <div class="form-group col-md-6">
                            <label>Conversion</label> <span class="text-danger">*</span>
                            <input v-model="form.conversion" type="text" name="conversion"  ref="conversion" id="conversion" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('conversion') }">
                            <has-error :form="form" field="conversion"></has-error>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Default Value</label> <span class="text-danger">*</span>
                            <input v-model="form.default_value" type="text" name="default_value"  ref="default_value" id="default_value" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('default_value') }">
                            <has-error :form="form" field="default_value"></has-error>
                        </div>
                        
                      </div>

                      <div class="row">
                           <div class="form-group col-md-6">
                            <label>Vehicle Details</label> <span class="text-danger">*</span>
                            <select data-style="" class="selectpicker w-100" multiple="multiple" v-model="form.vehicle_mapping_id" name="vehicle_mapping_id[]" ref="vehicle_mapping_id" id="vehicle_mapping_id" required>
                             <option value="">Select</option>
                              <option 
                                  v-for="(cty,index) in vehicles" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.vehicle_mapping_id">{{ cty.name }} - {{ cty.model }}</option>
                            </select>
                            <has-error :form="form" field="user_id"></has-error>
                        </div>
                        <div class="form-group col-md-6">
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
                      </div>
                        

                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button  v-show="editmode" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Update</button>
                        <button  v-show="!editmode" :disabled="isSending" type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
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
                writedatas : {},
                vehicles : [],
                ecu : [],
                writelist : [],
                fromData : "",
                form: new Form({
                    id : '',
                    name: '',
                    did: '',
                    hint: '',
                    security: '',
                    conversion: '',
                    range: '',
                    method: '',
                    default_value: '',
                    vehicle_mapping_id: [],
                     ecu : [],
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/writedata?page=' + page).then(({ data }) => (this.writedatas = data.data,this.fromData = data.data.from));

                   
            },
             updateWriteData(){
                 
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/writedata/post/'+this.form.id, data, config)
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
                        this.loadWriteData();
                    }, 800);
            },
            // updateWriteData(){
                 
            //     this.form.put('/api/writedata/'+this.form.id)
            //     .then((response) => {
            //         // success
            //         $('#addNew').modal('hide');
            //         Toast.fire({
            //           icon: 'success',
            //           title: response.data.message
            //         });
                     
            //             //  Fire.$emit('AfterCreate');

            //         this.loadWriteData();
            //     })
            //     .catch(() => {
            //         this.$Progress.fail();
            //     });

            // },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/writedataCheck/'+countriesData+'/'+name)
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
                setTimeout(() => {
                    $("#vehicle_mapping_id").val('').trigger('change');
                  $("#ecu_id").val('').trigger('change');
                }, 100);
                
                $('#addNew').modal('show');
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
                              this.form.delete('/api/writedata/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadWriteData();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadWriteData(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/writedata").then(({ data }) => (this.writedatas = data.data,this.fromData = data.data.from));
                }
            },
            loadVehicle(){
              axios.get("/api/vehicledetail/list").then(({ data }) => (this.vehicles = data.data));
          },
          loadEciDetail(){
              axios.get("/api/ecidetail/list").then(({ data }) => (this.ecu = data.data));
          },
           loadexport(){
              axios.get("/api/writedata/export").then(({ data }) => (this.writelist = data.data));
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


 createWriteData(){
                   const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                // var ecudata = $('#ecu_id').val();
                var data = new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/writedata/store', data, config)
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
                        this.loadWriteData();
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
                axios.post('/api/writedata/import',data, config)
                .then(function (response) {
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
                        this.loadWriteData();
                    }, 600);
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
                link.setAttribute("download", "Write Data.csv");
                link.click();
                },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadWriteData(); 
            this.loadEciDetail();
            this.loadVehicle();
            this.loadexport();
            this.loadLanguage();
        }
    }
</script>
