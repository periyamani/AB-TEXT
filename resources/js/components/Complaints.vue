<template>
  <section >
      <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <h4 class="page-title">Complaints
                            <!-- <button v-if="$role.isPermission('add_complaints_mode')" type="button" class="btn btn-rounded btn-success-rgba" @click="newModal" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-plus"></i> Add</button> -->
                            <!-- <button type="button" class="btn btn-rounded btn-primary-rgba" @click="onClick" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-pdf"></i> PDF</button> -->
                        </h4>
                        
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="breadcrumb-list">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Complaints</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
    <div class="contentbar" style="padding:8px 30px 30px 30px !important;">
        <div class="card m-b-30">

          <div class="card-body">
        
            <div class="card" v-if="$role.isPermission('complaint_list')">
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table" style="width: 150%;">
                  <thead>
                     <tr>
                                           <th>S.NO</th>
                                            <th>Dealer Name</th>
                                            <th>Mobile No</th>
                                            <th>Category</th>
                                            <th>Remark</th>
                                            <th>Status</th>
                                            <th>Open Date</th>
                                            <th>Close Date</th>
                                            <th>Close Remark</th>
                                            <th>Technician</th>
                                            <th>Attached Files</th>
                                            <th v-if="$role.isAction('update_complaint','delete_complaint')">Action</th>
                                        </tr>
                  </thead>
                  <tbody v-if="complaints.data.length != 0">


<tr v-for="(complaint,index) in complaints.data" :key="complaint.id">

                                       <td>{{fromData+index}}</td>
                                       <td >{{complaint.dealer_name}}</td>
                                        <td >{{complaint.dealer_mobile}}</td>
                                        <td >{{complaint.category}}</td>
                                        <td >{{complaint.remark}}</td>
                                        <td v-if="complaint.active == 'Open'"><span class="badge badge-pill badge-danger">Open</span></td>
                                        <td v-else><span class="badge badge-pill badge-success">Close</span></td>
                                        <td >{{complaint.open_date}}</td>
                                        <td >{{complaint.close_date}}</td>
                                        <td >{{complaint.close_remark}}</td>
                                        <td >{{complaint.technician_name}}</td>
                                        <td ><p v-if="complaint.complaint_audio">{{complaint.complaint_audio}} <span class="badge badge-pill badge-primary" style="cursor: pointer;" @click="audioPlay('audio',complaint.complaint_audio,complaint.audio_path)"><i class="fa fa-play" aria-hidden="true"></i> PLAY</span></p>
                                            <p v-if="complaint.complaint_video" >{{complaint.complaint_video}} <span class="badge badge-pill badge-primary" style="cursor: pointer;" @click="audioPlay('video',complaint.complaint_video,complaint.video_path)"><i class="fa fa-play" aria-hidden="true"></i> PLAY</span></p>
                                            <p v-if="complaint.complaint_document">{{complaint.complaint_document}} <span class="badge badge-pill badge-primary" style="cursor: pointer;" @click="audioPlay('doc',complaint.complaint_document,complaint.doc_path)"><i class="fa fa-eye" aria-hidden="true"></i> VIEW</span></p>

                                        </td>
                                        <td>

                                            <a v-if="$role.isPermission('update_complaint')" href="#" class="btn btn-rounded btn-primary-rgba" @click="editModal(complaint)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            &nbsp;
                                            <a v-if="$role.isPermission('delete_complaint')" href="#" class="btn btn-rounded btn-danger-rgba" @click="deleteData(complaint.id)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        </tr>
                     </tbody>
                     <tbody v-if="complaints.data.length == 0">
                        <tr>

                      <td colspan="4">No Data Found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="col-md-12">
                  <pagination :data="complaints" @pagination-change-page="getResults"></pagination>
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
                    <h5 class="modal-title" v-show="!editmode">Add Complaints</h5>
                    <h5 class="modal-title" v-show="editmode">Update Complaints</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- <form @submit.prevent="createUser"> -->

                <form ref="addform" @submit.prevent="editmode ? updateComplaints() : createComplaints()">
                    <div class="modal-body">
                       
                         <div class="form-group">
                            <label>Status</label> <span class="text-danger">*</span>

                            <select name="active" id="active" ref="active" v-model="form.active" class="form-control" :class="{ 'is-invalid': form.errors.has('active') }">
                                <option value="Open">Open</option>
                                <option value="Close">Close</option>
                            </select>
                            <has-error :form="form" field="active"></has-error>
                        </div>
                        <div class="form-group">
                            <label>Remark</label> <span class="text-danger">*</span>
                            <textarea type="text" name="close_remark" v-model="form.close_remark"  ref="close_remark" id="close_remark" cols="10" rows="3"
                             
                                class="form-control" :class="{ 'is-invalid': form.errors.has('close_remark') }"></textarea>
                            <has-error :form="form" field="close_remark"></has-error>
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

<div class="modal fade" id="audio" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Audio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="removeTag()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body" id="audioTag">
                                              
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" @click="removeTag()" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
<div class="modal fade" id="video" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="removeTag()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body" id="videoTag">
                                              
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" @click="removeTag()" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="documnt" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog zoomIn  animated modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="removeTag()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body" id="docTag">
                                              
                    </div>
                    <div class="modal-footer ui_footer">
                         <button type="reset" class="btn btn-rounded btn-danger-rgba" data-dismiss="modal" @click="removeTag()" style="padding: 3px 12px 3px 6px;border: 1px solid;"><i class="feather icon-remove"></i> Close</button>
                    </div>
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
                complaints : {},
                fromData : "",
                form: new Form({
                    id : '',
                    active: '',
                    close_remark: '',
                }),
            }
        },
        methods: {

            getResults(page = 1) {

                   
                  
                  axios.get('/api/complaint?page=' + page).then(({ data }) => (this.complaints = data.data,this.fromData = data.data.from));

                   
            },
            updateComplaints(){
                 
                this.form.put('/api/complaint/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                     
                        //  Fire.$emit('AfterCreate');

                    this.loadComplaints();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            checkduplicate(e){
                var name = this.$refs.name.value;
                var countriesData = this.$refs.country.value;
                if(name != '' && countriesData != ''){
                this.form.get('/api/complaintCheck/'+countriesData+'/'+name)
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
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            audioPlay(playType,name,pathName){
                if(playType == 'audio'){
                    // $("#audioName").attr("src",".."+pathName);
                    $('#audioTag').empty();
                    $('#audioTag').append('<audio controls style="width: 100%;">'+
                            '<source id="audioName" src="..'+pathName+'">'+
                            'Your browser does not support the audio tag.'+
                            '</audio> ')
                    $('#audio').modal('show');

                }else if(playType == 'video'){
                    $('#videoTag').empty();
                    $('#videoTag').append('<video controls width="470">'+
                                '<source src="..'+pathName+'">'+
                            '</video>')
                    $('#video').modal('show');
                }else{
                    
                    $('#docTag').empty();
                    $('#docTag').append('<iframe seamless width="770" height="450" '+
                    'src="..'+pathName+'">'+
                    '</iframe>')
                    $('#documnt').modal('show');
                }
            },
            removeTag(){
                $('#audioTag').empty();
                $('#videoTag').empty();
                $('#docTag').empty();
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
                              this.form.delete('/api/complaint/'+id).then(()=>{
                                      Swal.fire(
                                      'Deleted!',
                                      'Your file has been deleted.',
                                      'success'
                                      );
                                  // Fire.$emit('AfterCreate');
                                  this.loadComplaints();
                              }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                        }
                  })
          },
            loadComplaints(){
                if(this.$gate.isAdmin()){
                    axios.get("/api/complaint").then(({ data }) => (this.complaints = data.data,this.fromData = data.data.from));
                }
            },
            createComplaints(){
               this.form.post('/api/complaint')
              .then((response)=>{
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
                 
                  this.loadComplaints();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
            },
            onClick() {
            //    axios.get("/api/complaint")
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

             
            this.loadComplaints();
             
        }
    }
</script>
