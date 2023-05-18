<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">I/O Control
                            <button v-if="$role.isPermission('add_io_control_data')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
<button v-if="$role.isPermission('import_io_control_data')" type="button" class="btn btn-rounded btn-primary-rgba" @click="importCSV" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-csv"></i> Import CSV</button>
<button v-if="$role.isPermission('import_io_control_data')" type="button" class="btn btn-rounded btn-info-rgba" @click="exportCSV(iolist)" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-export"></i> Export CSV</button>
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">I/O Control</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('io_control_data_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Name</th>
                                            <th>DID</th>
                                            <th>Vehicle Detail</th>
                                            <th>ECU</th>
                                            <th v-if="$role.isAction('update_io_control_data','delete_io_control_data')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="iodatas.data.length != 0">


<tr v-for="(iodata,index) in iodatas.data" :key="iodata.id">

                                       <td>{{fromData+index}}</td>
                                        <td >
                                            <p v-for="(nameData) in JSON.parse(iodata.name)" :key="nameData.id">
                                                {{nameData.name}} : {{nameData.value}}
                                            </p>
                                        </td>
                                        <td >{{iodata.did}}</td>
                                        <td >
                                            <div v-for="(veh,index) in iodata.vehicle_name" :key="veh.id">
                                                <span>{{index+1}} . {{veh.name}}</span>
                                            </div>
                                        </td> 
                                          <td >
                                            <div v-for="(ecu,index) in iodata.ecu_name" :key="ecu.id">
                                                <span>{{index+1}} . {{ecu.name}}</span>
                                            </div>
                                        </td>
                                        <td>

                                            <a v-if="$role.isPermission('update_io_control_data')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(iodata)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_io_control_data')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(iodata.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="iodatas.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="iodatas" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add I/O Control</h5>
                    <h5 class="modal-title" v-show="editmode">Update I/O Control</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateIoData() : createIoData()">
                    <div class="modal-body">
                        <div class="row" id="languageList">
                        <!-- <div class="form-group col-md-6">
                            <label>English</label> <span class="text-danger">*</span>
                            <input type="text" name="name"  ref="name" id='name' required class="form-control" >
                        </div> -->
                           </div>
                      <div class="form-group">
                            <label>DID</label> <span class="text-danger">*</span>
                            <input v-model="form.did" type="text" name="did"  ref="did" id="did" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('did') }">
                            <has-error :form="form" field="did"></has-error>
                        </div>
                        
                        <div class="form-group">
                            <label>Vehicle Details</label> <span class="text-danger">*</span>
                            <select data-style="" class="selectpicker w-100" multiple="multiple" v-model="form.vehicle_mapping_id" name="vehicle_mapping_id[]" ref="vehicle_mapping_id" required id="vehicle_mapping_id">
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in vehicle" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.vehicle_mapping_id">{{ cty.name }} - {{ cty.model }}</option>
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
                iodatas : {},
                vehicle : [],
                 ecu : [],
                 iolist : [],
                fromData : "",
                form: new Form({
                    id : '',
                    did: '',
                    name: '',
                    vehicle_mapping_id: [],
                    ecu : [],
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/iodata?page=' + page).then(({ data }) => (this.iodatas = data.data,this.fromData = data.data.from));

                   
            },
             updateIoData(){
                 
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/iodata/post/'+this.form.id, data, config)
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
                        this.loadIoData();
                    }, 800);
            },
            // updateIoData(){
                 
            //     this.form.put('/api/iodata/'+this.form.id)
            //     .then((response) => {
            //         // success
            //         $('#addNew').modal('hide');
            //         Toast.fire({
            //           icon: 'success',
            //           title: response.data.message
            //         });
                     
            //             //  Fire.$emit('AfterCreate');

            //         this.loadIoData();
            //     })
            //     .catch(() => {
            //         this.$Progress.fail();
            //     });

            // },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/iodataCheck/'+countriesData+'/'+name)
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
                              this.form.delete('/api/iodata/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadIoData();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadIoData(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/iodata").then(({ data }) => (this.iodatas = data.data,this.fromData = data.data.from));
                }
            },
            loadVehicle(){
              axios.get("/api/vehicledetail/list").then(({ data }) => (this.vehicle = data.data));
          },
          loadEciDetail(){
              axios.get("/api/ecidetail/list").then(({ data }) => (this.ecu = data.data));
          },
          loadexport(){
              axios.get("/api/iodata/export").then(({ data }) => (this.iolist = data.data));
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
            createIoData(){
                   const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                // var ecudata = $('#ecu_id').val();
                var data = new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/iodata/store', data, config)
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
                        this.loadIoData();
                    }, 800);
            },
            // createIoData(){
            //    this.form.post('/api/iodata')
            //   .then((response)=>{
            //       if(response.data.message != "Already exist"){
            //             $('#addNew').modal('hide');
            //              Toast.fire({
            //             icon: 'success',
            //             title: response.data.message
            //       });
            //       }else{
            //            Toast.fire({
            //             icon: 'error',
            //             title: response.data.message
            //       });
            //       }
                 
            //       this.loadIoData();

            //   })
            //   .catch(()=>{

            //       Toast.fire({
            //           icon: 'error',
            //           title: 'Some error occured! Please try again'
            //       });
            //   })
            // },
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
                axios.post('/api/iodata/import',data, config)
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
                        this.loadIoData();
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
                link.setAttribute("download", "I/O Control.csv");
                link.click();
                },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadIoData();
            this.loadEciDetail();
            this.loadVehicle();
            this.loadexport();
            this.loadLanguage();
        }
    }
</script>
