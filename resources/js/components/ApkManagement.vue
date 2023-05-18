<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">APK Management
                            <button v-if="$role.isPermission('add_apk_management')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button>
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">APK Management</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('apk_management_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table" style="width: 180%;">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                           <th>Date</th>
                                            <th>Version</th>
                                            <th>Remark</th>
                                            <th>APK file</th>
                                            <th>Technician</th>
                                             <th>Approver</th>
                                             <th>Status</th>
                                              <th>Reason</th>

                                            <th v-if="$role.isAction('update_apk_management','delete_apk_management','approve_apk_management')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="apkmgmts.data.length != 0">


<tr v-for="(apkmgmt,index) in apkmgmts.data" :key="apkmgmt.id">

                                       <td>{{fromData+index}}</td>
                                       <td style="font-size: 14px;">
                                           Created Date  : <b>{{moment(apkmgmt.created_at).format("DD-MM-YYYY")}}</b><br>
                                           <span v-if="apkmgmt.approved_date">
                                           {{apkmgmt.approval_status}} Date  :<b v-if="apkmgmt.approved_date">{{moment(apkmgmt.approved_date).format("DD-MM-YYYY")}}</b><b v-else>-</b>
                                           </span>
                                        </td>
                                        <td >{{apkmgmt.version}}</td>
                                        <td >{{apkmgmt.remark}}</td>
                                        <td >{{apkmgmt.apk_file}}</td> 
                                        <td >{{apkmgmt.technician_name}}</td>
                                        <td >{{apkmgmt.approver_name}}</td>
                                         <td v-if="apkmgmt.approval_status == 'Pending'"><span class="badge badge-pill badge-warning">{{apkmgmt.approval_status}}</span></td>
                                        <td v-else-if="apkmgmt.approval_status == 'Rejected'"><span class="badge badge-pill badge-danger">{{apkmgmt.approval_status}}</span></td>
                                        <td v-else><span class="badge badge-pill badge-success">{{apkmgmt.approval_status}}</span></td>
                                        <td >{{apkmgmt.approver_remark}}</td>
                                        <td>

                                            <a v-if="$role.isPermission('update_apk_management')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(apkmgmt)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_apk_management')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(apkmgmt.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                            &nbsp;
                                             <a v-if="$role.isPermission('approve_apk_management')" href="#" class="btn btn-rounded btn-warning-rgba" @click="approveData(apkmgmt)">
                                                <i class="fa fa-thumbs-up yellow"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="apkmgmts.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="apkmgmts" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add APK Management</h5>
                    <h5 class="modal-title" v-show="editmode">Update APK Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form id="addForm" ref="addform" @submit.prevent="editmode ? updateApkMgmt() : createApkMgmt()">
                    <div class="modal-body">
                      
                        

                        <div class="form-group">
                            <label>Version</label> <span class="text-danger">*</span>
                            <input v-model="form.version" type="text" name="version"  ref="version" id="version" required
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('version') }">
                            <has-error :form="form" field="version"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Remark</label> <span class="text-danger">*</span>
                            <textarea type="text" name="remark"  ref="remark" id="remark" cols="10" rows="3"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('remark') }"></textarea>
                            <has-error :form="form" field="remark"></has-error>
                        </div>
                         <div class="form-group">
                            <label>APK File</label>
                            <input type="file" accept=".apk" name="apk_file"  ref="apk_file" id="apk_file" v-on:change="onChange"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('apk_file') }">
                            <has-error :form="form" field="apk_file"></has-error>
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

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve APK Management</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addApprove" @submit.prevent="approveApkMgmt()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status</label> <span class="text-danger">*</span>
                            <select class="form-control" v-model="form.approval_status" name="approval_status" id="approval_status" ref="approval_status" required v-on:change="getTextData">
                             <!-- <option value="">Select</option> -->
                              <option value="Pending">Pending</option>
                              <option value="Approved">Approved</option>
                              <option value="Rejected">Rejected</option>
                            </select>
                            <has-error :form="form" field="file_status"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Remark</label> <span class="text-danger">*</span>
                            <textarea type="text" v-model="form.approver_remark" name="approver_remark"  ref="approver_remark" id="approver_remark" cols="10" rows="3"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('approver_remark') }"></textarea>
                            <has-error :form="form" field="approver_remark"></has-error>
                        </div>
                       
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Cancel</button>
                        <button type="submit" class="btn btn-rounded btn-success-rgba" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-save"></i> Save</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>

    </div>
  </section>
</template>

<script>
import moment from 'moment';
    export default {
        data () {
            return {
                editmode: false,
                apkmgmts : {},
                fromData : "",
                file: '',
                moment: moment,
                form: new Form({
                    id : '',
                    version: '',
                    apk_file: '',
                     remark: '',
                     approver_remark : '',
                    approval_status : '',
                }),
            }
        },
        methods: {
            onChange(e) {
                this.file = e.target.files[0];
            },
            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('/api/apkmgmt?page=' + page).then(({ data }) => (this.apkmgmts = data.data,this.fromData = data.data.from));

                  this.$Progress.finish();
            },
            updateApkMgmt(){
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                axios.post('/api/apkmgmt/post/'+this.form.id, data, config)
                .then(function (response) {
                    // success
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
                        this.loadApkMgmt();
                    }, 800);
            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                axios.post('/api/apkmgmt', data, config)
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
                $('#addNew').modal('show');
                this.form.fill(category);
                $("#remark").val(category.remark);
                
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                 $("#ecu_id").val('').trigger('change')
                $("#remark").val("");
                $('#addNew').modal('show');
            },
            approveData(category){
                this.form.reset();
                $("#approver_remark").val("");
                $('#approveModal').modal('show');
                this.form.fill(category);
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
                              this.form.delete('/api/apkmgmt/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadApkMgmt();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
          approveApkMgmt(){
                this.form.put('/api/apkmgmt/approve/'+this.form.id)
                .then((response) =>  {
                    // success
                    $('#approveModal').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                })
                .catch(() => {
                    this.$Progress.fail();
                });
                    setTimeout(() => {
                        this.loadApkMgmt();
                    }, 800);
            },
            loadApkMgmt(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/apkmgmt").then(({ data }) => (this.apkmgmts = data.data,this.fromData = data.data.from));
                }
            },
            createApkMgmt(){
                // var countriesData = $('#functionality_id').select2("val");
                 const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
                var data =   new FormData($("#addForm")[0]);
                axios.post('/api/apkmgmt', data, config)
                    .then(function (response) {
                        if(response.data.message != "Already exist"){
                        $('#addNew').modal('hide');
                        // $("#ecu_id").val('').trigger('change');
                            Toast.fire({
                            icon: 'success',
                            title: response.data.message
                        });
                        }else{
                            //  $("#ecu_id").val(ecudata).trigger('change')
                            Toast.fire({
                                icon: 'error',
                                title: response.data.message
                        });
                        }
                       
                    })
                    .catch(function (err) {
                         Toast.fire({
                            icon: 'error',
                            title: 'Some error occured! Please try again'
                        });
                    });
                    setTimeout(() => {
                        this.loadApkMgmt();
                    }, 800);
            
            },
            onClick() {
            //    axios.get("/api/apkmgmt")
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

            this.$Progress.start();
            this.loadApkMgmt();
            this.$Progress.finish();
        }
    }
</script>
