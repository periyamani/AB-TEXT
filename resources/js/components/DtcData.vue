<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">DTC
                            <button v-if="$role.isPermission('add_dtc_data')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
<button v-if="$role.isPermission('import_dtc_data')" type="button" class="btn btn-rounded btn-primary-rgba" @click="importCSV" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-csv"></i> Import CSV</button>
<button v-if="$role.isPermission('import_dtc_data')" type="button" class="btn btn-rounded btn-info-rgba" @click="exportCSV(dtclist)" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="ti-export"></i> Export CSV</button>
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">DTC</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('dtc_data_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table" style="width:130%;">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>DTC Code</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th style="width: 23px;">Troubleshoot</th>
                                            <th>Vehicle Detail</th>
                                            <th>ECU</th>
                                            <th v-if="$role.isAction('update_dtc_data','delete_dtc_data')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="dtcdatas.data.length != 0">


<tr v-for="(dtcdata,index) in dtcdatas.data" :key="dtcdata.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{dtcdata.dtc_code}}</td>
                                        <td >
                                            <p v-for="(nameData) in JSON.parse(dtcdata.name)" :key="nameData.id">
                                                {{nameData.name}} : {{nameData.value}}
                                            </p>
                                        </td>
                                        <td >
                                             <p v-for="(nameDatas) in JSON.parse(dtcdata.description)" :key="nameDatas.id">
                                                {{nameDatas.name}} : {{nameDatas.value}}
                                            </p>
                                        <td >{{dtcdata.troubleshoot}}</td>
                                        <td >
                                            <div v-for="(veh,index) in dtcdata.vehicle_name" :key="veh.id">
                                                <span>{{index+1}} . {{veh.name}}</span>
                                            </div>
                                        </td> 
                                          <td >
                                            <div v-for="(ecu,index) in dtcdata.ecu_name" :key="ecu.id">
                                                <span>{{index+1}} . {{ecu.name}}</span>
                                            </div>
                                        </td>
                                        <td>

                                            <a v-if="$role.isPermission('update_dtc_data')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(dtcdata)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_dtc_data')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(dtcdata.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="dtcdatas.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="dtcdatas" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add DTC</h5>
                    <h5 class="modal-title" v-show="editmode">Update DTC</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateDtcData() : createDtcData()">
                    <div class="modal-body">
                      <div class="form-group">
                            <label>DTC Code</label> <span class="text-danger">*</span>
                            <input v-model="form.dtc_code" type="text" name="dtc_code"  ref="dtc_code" id="dtc_code" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('dtc_code') }">
                            <has-error :form="form" field="dtc_code"></has-error>
                        </div>
                        <h6>Name</h6>
                        <div class="row" id="languageList">
                        </div>
                        <hr style="margin-top: 0 !important;">
                         <h6>Description</h6>
                        <div class="row" id="descList">
                        </div>
                        <hr style="margin-top: 0 !important;">
                        <!-- <div class="form-group">
                            <label>Description</label> <span class="text-danger">*</span>
                            <input v-model="form.description" type="text" name="description"  ref="description" id="description" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                            <has-error :form="form" field="description"></has-error>
                        </div> -->
                        <div class="form-group">
                            <label>Troubleshoot</label> <span class="text-danger">*</span>
                            <input v-model="form.troubleshoot" type="text" name="troubleshoot"  ref="troubleshoot" id="troubleshoot" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('troubleshoot') }">
                            <has-error :form="form" field="troubleshoot"></has-error>
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
                dtcdatas : {},
                vehicle : [],
                ecu : [],
                dtclist : [],
                fromData : "",
                form: new Form({
                    id : '',
                    dtc_code: '',
                    name: '',
                    description: '',
                    troubleshoot: '',
                    vehicle_mapping_id: [],
                     ecu : [],
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/dtcdata?page=' + page).then(({ data }) => (this.dtcdatas = data.data,this.fromData = data.data.from));

                   
            },
            updateDtcData(){
                 
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/dtcdata/post/'+this.form.id, data, config)
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
                        this.loadDtcData();
                    }, 800);
            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/dtcdataCheck/'+countriesData+'/'+name)
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
                        $("#name"+value.id).val(value.value);
                    })
                }else{
                    $.each(this.languages,function(key,value){
                        $("#name"+value.id).val('');
                    })
                }
                 var nameDatas = JSON.parse(category.description);
                if(nameDatas){
                    $.each(nameDatas,function(key,value){
                        $("#desc"+value.id).val(value.value);
                    })
                }else{
                    $.each(this.languages,function(key,value){
                        $("#desc"+value.id).val('');
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
                              this.form.delete('/api/dtcdata/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadDtcData();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadDtcData(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/dtcdata").then(({ data }) => (this.dtcdatas = data.data,this.fromData = data.data.from));
                }
            },
            loadVehicle(){
              axios.get("/api/vehicledetail/list").then(({ data }) => (this.vehicle = data.data));
          },
          loadexport(){
              axios.get("/api/dtcdata/export").then(({ data }) => (this.dtclist = data.data));
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
                            '<input type="text" name="name[]"  ref="name'+value.id+'" id="name'+value.id+'" required class="form-control" >'+
                        '</div>');
                    $('#descList').append('<div class="form-group col-md-6">'+
                            '<label>'+value.name+'</label> <span class="text-danger">*</span>'+
                            '<input type="text" name="description[]"  ref="desc'+value.id+'" id="desc'+value.id+'" required class="form-control" >'+
                        '</div>')
                    });
                }
            },

            createDtcData(){
                   const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                // var ecudata = $('#ecu_id').val();
                var data = new FormData($("#addForm")[0]);
                this.showAddImageBtn = false;
                axios.post('/api/dtcdata/store', data, config)
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
                axios.post('/api/dtcdata/import',data, config)
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
                        this.loadDtcData();
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
                link.setAttribute("download", "DTC.csv");
                link.click();
                },
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadDtcData();
            this.loadEciDetail();
            this.loadVehicle();
            this.loadexport();
            this.loadLanguage();
        }
    }
</script>
