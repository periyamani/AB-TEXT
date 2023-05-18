<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="container">
                 <h4 class="font-weight-bold">VIEW MEMBER DETAILS</h4>
          <hr style="margin-bottom:9px !important;">
          <p style="    margin-bottom: 4px !important;">
            <strong>Mobile</strong> : <span v-if="basic.mobile_verify != 0" style="color:#73AD21;"><i class="fa fa-check-circle"></i></span><span v-if="basic.mobile_verify == 0" style="color:red;"><i class="fa fa-times-circle"></i></span> &nbsp;&nbsp;&nbsp;
            <strong>Email</strong> : <span v-if="basic.email_verify != 0" style="color:#73AD21;"><i class="fa fa-check-circle"></i></span><span v-if="basic.email_verify == 0" style="color:red;"><i class="fa fa-times-circle"></i></span> &nbsp;&nbsp;&nbsp;
            <strong>Proof</strong> : <span v-if="basic.proof_verify != 0" style="color:#73AD21;"><i class="fa fa-check-circle"></i></span><span v-if="basic.proof_verify == 0" style="color:red;"><i class="fa fa-times-circle"></i></span> &nbsp;&nbsp;&nbsp;
            <strong>Subscription</strong> : <span v-if="basic.subscription != 0" style="color:#73AD21;"><i class="fa fa-check-circle"></i></span><span v-if="basic.subscription == 0" style="color:red;"><i class="fa fa-times-circle"></i></span>
            </p>
    
            <div class="row">
                <div class="col-md-6 ui_column">
                    <div class="ui_border">
                    <h5 class="font-weight-bold text-center">Basic Information</h5>
                    <hr style="margin-bottom:9px !important;">
                    
                    <table class="table table-borderless basic">
                   
                    <tbody>
                        <tr>
                        <td style="width: 33%;">Name :</td>
                        <th>{{basic.name}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Created For :</td>
                        <th>{{basic.profile_for}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Email :</td>
                        <th>{{basic.email}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Mobile No :</td>
                        <th>{{basic.mobile}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Whatsapp No :</td>
                        <th>{{family.whatsapp_number}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">DOB & Time :</td>
                        <th>{{basic.dob}} & {{personal.time_of_birth}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Age :</td>
                        <th>{{basic.age}} Yrs</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Gender :</td>
                        <th>{{basic.gender}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Marital Status :</td>
                        <th>{{basic.marital_status}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Religion :</td>
                        <th>{{basic.religionName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Caste :</td>
                        <th>{{basic.casteName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Star :</td>
                        <th>{{personal.starName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Rasi :</td>
                        <th>{{personal.rasiName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Dhosam :</td>
                        <th>{{personal.dhosamName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Birth Order :</td>
                        <th>{{personal.birth_order}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">City :</td>
                        <th>{{basic.cityName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">District :</td>
                        <th>{{basic.districtName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">State :</td>
                        <th>{{basic.stateName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Country :</td>
                        <th>{{basic.countryName}}</th>
                        </tr>

                    </tbody>
                    </table>
                    </div>
                </div>
                <div class="col-md-6 ui_column">
                    <div class="ui_border">
                    <h5 class="font-weight-bold text-center">Personal Information</h5>
                    <hr style="margin-bottom:9px !important;">
                     <table class="table table-borderless basic">
                   
                    <tbody>
                        <tr>
                        <td style="width: 33%;">Subcaste :</td>
                        <th>{{personal.subcaste}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Gothra :</td>
                        <th>{{personal.gothra}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Education :</td>
                        <th>{{personal.educationName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Occupation :</td>
                        <th>{{personal.occupationName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Income :</td>
                        <th>{{personal.income}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Blood Group :</td>
                        <th>{{personal.blood_group}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Height :</td>
                        <th>{{feetData}} / {{cm}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Weight :</td>
                        <th>{{personal.weight}} Kgs</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Body Type :</td>
                        <th>{{personal.body_type}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Complexion :</td>
                        <th>{{personal.complexion}}</th>
                        </tr>
                       
                        <tr>
                        <td style="width: 33%;">Eating Habit :</td>
                        <th>{{personal.eating_habit}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Smoking Habit :</td>
                        <th>{{personal.smoking_habit}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Drinking Habit :</td>
                        <th>{{personal.drinking_habit}}</th>
                        </tr>
                       
                        
                        <tr>
                        <td style="width: 33%;">Proof Type :</td>
                        <th>{{personal.proof_type}}</th>
                        </tr>
                       
                        <tr>
                        <td style="width: 33%;">Proof Number :</td>
                        <th>{{personal.proof_number}}</th>
                        </tr>
                       
                       
                        <tr>
                        <td style="width: 33%;">Habbies :</td>
                        <th>{{habbies}}</th>
                        </tr>
                       
                         <tr>
                        <td style="width: 33%;">Interest :</td>
                        <th>{{interest}}</th>
                        </tr>
                        

                    </tbody>
                    </table>
                </div>
                </div>
            </div>

<div class="row">
   <div class="col-md-6 ui_column">
                    <div class="ui_border">
                    <h5 class="font-weight-bold text-center">Partner Information</h5>
                    <hr style="margin-bottom:9px !important;">
                     <table class="table table-borderless basic">
                   
                    <tbody>
                        <tr>
                        <td style="width: 33%;">Martial Status :</td>
                        <th>{{partner.marital_status}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Religion :</td>
                        <th>{{partner.religionName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Caste :</td>
                        <th>{{partner.casteName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Star :</td>
                        <th>{{partner.starName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Rasi :</td>
                        <th>{{partner.rasiName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Dhosam :</td>
                        <th>{{partner.dhosamName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Education :</td>
                        <th>{{partner.educationName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Occupation :</td>
                        <th>{{partner.occupationName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Income :</td>
                        <th>{{partner.annual_income}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Mother Tongue :</td>
                        <th>{{partner.tongueName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Age :</td>
                        <th>{{partner.from_age}} Yrs TO {{partner.to_age}} Yrs</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Height :</td>
                        <th>{{feetDataPartfrom}} / {{cmPartfrom}} TO {{feetDataPartto}} / {{cmPartto}}</th> 
                        </tr>
                        <tr>
                        <td style="width: 33%;">Weight :</td>
                        <th>{{partner.from_weight}} Kgs TO {{partner.to_weight}} Kgs</th>
                        </tr>
                       
                        <tr>
                        <td style="width: 33%;">Eating Habit :</td>
                        <th>{{partner.eating_habit}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Smoking Habit :</td>
                        <th>{{partner.smoking_habit}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Drinking Habit :</td>
                        <th>{{partner.drinking_habit}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">City :</td>
                        <th>{{partner.cityName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">District :</td>
                        <th>{{partner.districtName}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">State :</td>
                        <th>{{partner.stateName}}</th>
                        </tr>
                         <tr>
                        <td style="width: 33%;">Country :</td>
                        <th>{{partner.countryName}}</th>
                        </tr>
                        

                    </tbody>
                    </table>
                </div>
   </div>
                <div class="col-md-6 ui_column">
                    <div class="ui_border">
                    <h5 class="font-weight-bold text-center">Family Information</h5>
                    <hr style="margin-bottom:9px !important;">
                    <table class="table table-borderless basic">
                   
                    <tbody>
                        <tr>
                        <td style="width: 33%;">Father Name &<br> Occupation:</td>
                        <th>{{family.father_name}} &<br> {{family.father_occupation}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Mother Name &<br> Occupation:</td>
                        <th>{{family.mother_name}} &<br> {{family.mother_occupation}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">No of Brothers &<br> Married :</td>
                        <th>{{family.no_of_brothers}} &<br> {{family.married_brothers}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">No of Sisters &<br> Married :</td>
                        <th>{{family.no_of_sisters}} &<br> {{family.married_sisters}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Family Type :</td>
                        <th>{{family.family_type}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Family Status :</td>
                        <th>{{family.family_status}} Yrs</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Family Value :</td>
                        <th>{{family.family_value}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Native Location :</td>
                        <th>{{family.native_location}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Pincode :</td>
                        <th>{{family.pincode}}</th>
                        </tr>
                        <tr>
                        <td style="width: 33%;">Hide Contact :</td>
                        <th>{{family.hide_contact}}</th>
                        </tr>
                    </tbody>
                    </table>
                    <hr style="margin-bottom:9px !important;">
                    <h5 class="font-weight-bold text-center">Photos</h5>
                    <hr style="margin-bottom:9px !important;">
                     <table class="table table-borderless basic">
                   
                    <tbody>
                        <tr>
                        <td id="showPhoto"> </td>
                        <td id="showProof"></td>
                        </tr>
                        
                    </tbody>
                    </table>

                    </div>
                </div>
            </div>

            <!-- /.card -->
          </div>
        </div>
    </div>

        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                basic : {},
                personal : {},
                family : {},
                partner : {},
            }
        },
        methods: {

           loadData(){
               console.log(this.$route.query.id); 
               var id = this.$route.query.id;
               if(this.$gate.isAdmin()){
                 
                  
                axios.get("/api/fulldetails/"+id).then(({ data }) => {
                    
                    this.basic = data.data;
                    this.photos = '';
                    if(this.basic.photoname){
                        $('#showPhoto').html('<img src="../images/profile_image/'+this.basic.photoname+'" width="180" alt="Profile">');
                    }else{
                        $('#showPhoto').html('No Profile Image')
                    }
                });

                axios.get("/api/personaldetails/"+id).then(({ data }) => {
                    
                    this.personal = data.data;
                    this.habbies = '';
                    this.interest = '';
                    if(this.personal.habbies){
                    var arr_len = this.personal.habbies.substring(1,this.personal.habbies.length-1);
                    this.habbies = arr_len.replace(/[&\/\\#+()$~%.'":*?<>{}]/g, '');
                    }
                    if(this.personal.interest){
                    var arr_len = this.personal.interest.substring(1,this.personal.interest.length-1);
                    this.interest = arr_len.replace(/[&\/\\#+()$~%.'":*?<>{}]/g, '');
                    }

                    this.feetData = '-';
                    this.cm = '-';
                    if(this.personal.height){
                                    
                    var feet = this.personal.height.split('.');
                    if(feet[1] == '12'){
                        this.feetData = feet[0] + 1;
                    }else{
                                    this.feetData = feet[0]+'ft '+feet[1]+'in';
                                }
                                this.cm = (this.personal.height * 30.48).toFixed(1)+' cm';
                                }
                    if(this.personal.photoname){
                        $('#showProof').html('<img src="../images/proof_image/'+this.personal.photoname+'" width="180" alt="Proof">');
                    }else{
                        $('#showProof').html('No Profile Image')
                    }

                });
                axios.get("/api/familydetails/"+id).then(({ data }) => {
                    
                    this.family = data.data;

                });

                axios.get("/api/partnerdetails/"+id).then(({ data }) => {
                    
                    this.partner = data.data;

                    this.feetDataPartfrom = '-';
                    this.cmPartfrom = '-';
                    if(this.partner.from_height){
                                    
                    var feet = this.partner.from_height.split('.');
                    if(feet[1] == '12'){
                        this.feetDataPartfrom = feet[0] + 1;
                    }else{
                        this.feetDataPartfrom = feet[0]+'ft '+feet[1]+'in';
                        }
                        this.cmPartfrom = (this.partner.from_height * 30.48).toFixed(1)+' cm';
                    }

                    this.feetDataPartto = '-';
                    this.cmPartto = '-';
                    if(this.partner.to_height){
                                    
                    var feet = this.partner.to_height.split('.');
                    if(feet[1] == '12'){
                        this.feetDataPartto = feet[0] + 1;
                    }else{
                        this.feetDataPartto = feet[0]+'ft '+feet[1]+'in';
                        }
                        this.cmPartto = (this.partner.to_height * 30.48).toFixed(1)+' cm';
                    }

                });

                   
                }
           }

        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {

             
            this.loadData();
             
        }
    }
</script>
