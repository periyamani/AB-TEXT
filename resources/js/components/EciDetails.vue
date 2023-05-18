<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">ECU Details
                            <button v-if="$role.isPermission('add_ecu')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ECU Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('ecu_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>ECU Name</th>
                                            <th>ECU Number</th>
                                            <th>OEM</th>
                                            <th>ECU Functions</th>
                                            <th v-if="$role.isAction('update_ecu','delete_ecu')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="ecidetails.data.length != 0">


<tr v-for="(ecidetail,index) in ecidetails.data" :key="ecidetail.id">

                                       <td>{{fromData+index}}</td>
                                        <td >{{ecidetail.name}}</td>
                                        <td >{{ecidetail.serial_no}}</td>
                                        <td >{{ecidetail.oem}}</td>
                                        <td >
                                            <div v-for="(func) in ecidetail.function_name" :key="func.id">
                                                <span>{{func.name}}</span>
                                            </div>
                                        </td> 
                                        <td>

                                            <a v-if="$role.isPermission('update_ecu')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(ecidetail)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_ecu')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(ecidetail.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="ecidetails.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="ecidetails" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add ECU Details</h5>
                    <h5 class="modal-title" v-show="editmode">Update ECU Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form ref="addform" @submit.prevent="editmode ? updateEciDetail() : createEciDetail()">
                    <div class="modal-body">
                      
                        

                        <div class="form-group">
                            <label>ECU Name</label> <span class="text-danger">*</span>
                            <input v-model="form.name" type="text" name="name"  ref="name" id="name" required @keydown="getFunDetail()"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="form-group">
                            <label>ECU Number</label> <span class="text-danger">*</span>
                            <input v-model="form.serial_no" type="text" name="serial_no"  ref="serial_no" id="serial_no" required @keydown="getFunDetail()"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('serial_no') }">
                            <has-error :form="form" field="serial_no"></has-error>
                        </div>

                        <div class="form-group">
                            <label>OEM</label> <span class="text-danger">*</span>
                            <input v-model="form.oem" type="text" name="oem"  ref="oem" id="oem" required @keydown="getFunDetail()"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('serial_no') }">
                            <has-error :form="form" field="oem"></has-error>
                        </div>

                        <div class="form-group">
                            <label>ECU Functions</label> <span class="text-danger">*</span>
                            <select data-style="" class="selectpicker w-100" multiple="multiple"  v-model="form.ecu_functionality" name="ecu_functionality" id="ecu_functionality" ref="ecu_functionality" required >
                             <!-- <option value="">Select</option> -->
                              <option 
                                  v-for="(cty,index) in vfn" :key="index"
                                  :value="cty.id"
                                  :selected="index == form.ecu_functionality">{{ cty.name }}</option>
                            </select>
                            <has-error :form="form" field="ecu_functionality"></has-error>
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
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                ecidetails : {},
                vfn : [],
                fromData : "",
                form: new Form({
                    id : '',
                    name: '',
                    serial_no : '',
                    oem: '',
                    ecu_functionality: '',
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/ecidetail?page=' + page).then(({ data }) => (this.ecidetails = data.data,this.fromData = data.data.from));

                   
            },
            updateEciDetail(){
                 
                this.form.functionality_id = JSON.stringify($('#functionality_id').val());
                this.form.put('/api/ecidetail/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadEciDetail();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/ecidetailCheck/'+countriesData+'/'+name)
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
                 var parseFunction = JSON.parse(category.ecu_functionality);
                $("#ecu_functionality").val(parseFunction).trigger('change');
                $('#addNew').modal('show');
                
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $("#ecu_functionality").val('').trigger('change')
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
                              this.form.delete('/api/ecidetail/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadEciDetail();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadEciDetail(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/ecidetail").then(({ data }) => (this.ecidetails = data.data,this.fromData = data.data.from));
                }
            },
            loadFunction(){
              axios.get("/api/vehiclefunction/list").then(({ data }) => (this.vfn = data.data));
          },
            createEciDetail(){
                // var countriesData = $('#functionality_id').select2("val");
                this.form.functionality_id = JSON.stringify($('#functionality_id').val());
               this.form.post('/api/ecidetail')
              .then((response)=>{
                  if(response.data.message != "Already exist"){
                        $('#addNew').modal('hide');
                        $("#functionality_id").val('').trigger('change')
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
                 
                  this.loadEciDetail();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
            },
            getFunDetail(){
                // var func = $('#functionality_id').val();
                // console.log(func);
                // setTimeout(() => {
                //     $("#functionality_id").val(func).trigger('change');
                // }, 500);
                
            },
            onClick() {
            //    axios.get("/api/ecidetail")
            //     .then((response) => {
            //          var fileURL = window.URL.createObjectURL(new Blob([response.data.data.data]));
            //          var fileLink = document.createElement('a');
   
            //          fileLink.href = "Mohanraj";
            //          fileLink.setAttribute('download', 'file.pdf');
            //          document.body.appendChild(fileLink);
   
            //          fileLink.click();
            //     });
    //         var pdf = new jsPDF();
    // var element = document.getElementById('facture');
    // var width= element.style.width;
    // var height = element.style.height;
    // html2canvas(element).then(canvas => {
    //     var image = canvas.toDataURL('image/png');
    //     pdf.addImage(image, 'JPEG', 15, 40, width, height);
    //     pdf.save('facture' + moment(this.facture.date_debut).format('LL') + '_' + moment(this.facture.date_fin).format('LL') + '.pdf');
    // });
          }
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadEciDetail();
            this.loadFunction();
             
        }
    }
</script>
