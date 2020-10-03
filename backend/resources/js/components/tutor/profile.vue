<template>

  <div class="profile-page">
    <b-form @submit.prevent="submit" >
<div class="profile container">
      <div class="tutor-profile">
         <div class="avatar-profile">
            <b-avatar variant="secondary" :src="details.profile" class="profile-img" size="7rem">
                <template v-slot:badge><label for="up" class="m-0"><b-icon icon="pencil" size="1rem"></b-icon></label>
               
                <b-form-file
                id="up"
                 hidden
                  accept="image/*"
                  plain
                  @change="handleFileChange($event)"
                ></b-form-file>
         
                </template>

            </b-avatar>
              
           
          </div>
        <div class="profile-inner">
          <h6>Personal Information</h6>
          <div class="personal-form">
            <b-form>
              <b-form-row>
                <b-col md="6">
                  <b-form-group label="Name">
                  <div class="after-edit">
                      <b-form-input placeholder v-model="details.name"></b-form-input>
                  </div>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Email">
                  <div class="after-edit">
                      <b-form-input placeholder v-model="details.email"></b-form-input>
                  </div>
                  </b-form-group>
                </b-col>
              </b-form-row>
              <b-form-row>
                <b-col md="6">
                  <b-form-group label="Date of Birth">
                   <div class="after-edit">
                      <b-form-datepicker id="example-datepicker" v-model="details.dob"></b-form-datepicker>
                   </div>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Address">
                   <div class="after-edit">
                      <b-form-input placeholder v-model="details.address"></b-form-input>
                   </div>
                  </b-form-group>
                </b-col>
              </b-form-row>
              <b-form-row>
                <b-col md="6">
                  <b-form-group label="Gender">
               <div class="after-edit">
                  <b-form-select class="toCaps" v-model="details.gender" :options="gender"></b-form-select>
               </div>
              </b-form-group>
                </b-col>
                <b-col md="6">
                <b-form-group label="Phone Number">
                    <b-form-input v-model="details.phone" type="tel"></b-form-input>
                </b-form-group>
                </b-col>
              </b-form-row>
              <b-form-row>
                <b-col md="6">
                  <b-form-group label="State">
                  <div class="after-edit">
                      <b-form-input placeholder v-model="details.state"></b-form-input>
                  </div>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Local Goverment Area">
                   <div class="after-edit"> <b-form-input placeholder v-model="details.lga"></b-form-input></div>
                  </b-form-group>
                </b-col>
              </b-form-row>
               <b-form-row>
                <b-col md="12">
                  <b-form-group label="Bio">
                  <div class="after-edit">
                      <b-form-textarea placeholder v-model="details.bio"></b-form-textarea>
                  </div>
                  </b-form-group>
                </b-col>
               
              </b-form-row>
            </b-form>
          </div>
        </div>
        <div class="profile-inner">
          <h6>Experience</h6>
          <div class="personal-form">
            <b-form>
               <b-form-row>
                 <b-col md="6">
                  <b-form-group label="Institution">
                   <div class="after-edit"> <b-form-input placeholder v-model="details.institution"></b-form-input></div>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Current Grade Level">
                    <div class="after-edit">
                      <b-form-input v-model="details.grade_level" ></b-form-input>
                      </div>
                  </b-form-group>
                </b-col>
               
              </b-form-row>
              <b-form-row>
                <b-col md="6">
                  <b-form-group label="Subjects">
                
                  <div v-if="details.subjects" >
                   <b-row>
                     <b-col cols="6" sm="4" v-for="(item,idx) in JSON.parse(details.subjects)" :key="idx">
                         <p class=" toCaps" >
                   {{item}}
                   </p>
                     </b-col>
                   </b-row>
                  </div>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Area of Specialization">
                   <div v-if="details.expertise" >
                      <b-row>
                     <b-col cols="6" sm="4" v-for="(item,idx) in JSON.parse(details.expertise)" :key="idx">
                         <p class="p-2 toCaps" >
                   {{item}}
                   </p>
                     </b-col>
                   </b-row>
                  </div>
                  </b-form-group>
                </b-col>
              </b-form-row>
              <!-- <b-form-row>
                <b-col md="6">
                  <b-form-group label="Date of Entry">
                   <b-form-datepicker id="example-datepicker" v-model="details.doe"></b-form-datepicker>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Faculty">
                  <div class="after-edit">  <b-form-input placeholder v-model="details.faculty"></b-form-input></div>
                  </b-form-group>
                </b-col>
              </b-form-row>
              <b-form-group label="Department">
               <div class="after-edit"> <b-form-input placeholder v-model="details.department"></b-form-input></div>
              </b-form-group> -->
             
            </b-form>
          </div>
        </div>
        <div class="profile-inner">
          <h6>Bank Information</h6>
          <div class="personal-form">
            <b-form>
              <b-form-row>
                <b-col md="6">
                  <b-form-group label="Bank Name">
                   <div class="after-edit"> <b-form-input placeholder v-model="details.bank_name"></b-form-input></div>
                  </b-form-group>
                </b-col>
                <b-col md="6">
                  <b-form-group label="Account Number">
                   <div class="after-edit"> <b-form-input placeholder v-model="details.bank_no"></b-form-input></div>
                  </b-form-group>
                </b-col>
              </b-form-row>
            
              <b-form-group label="BVN">
               <div class="after-edit"> <b-form-input placeholder v-model="details.bvn"></b-form-input></div>
              </b-form-group>
             
            </b-form>
          </div>
        </div>
        <div class="save-btn">
          <button class="btn btn-save" type="submit">Save</button>
        </div>
      </div>
    </div>
    </b-form>
  </div>
</template>

<script>
export default {
  props: ["tutor"],
  data() {
    return {
      details: {},
      selected: null,
      gender: [
        { value: null, text: "Please select gender" },
        { value: "female", text: "female" },
        { value: "male", text: "male" },
      ],
      gradelevel: [
        { value: null, text: "Please select grade level" },
        { value: "a", text: "Level 1" },
        { value: "b", text: "Level 2" },
        { value: "c", text: "Level 3" },
      ],
       cloudinary: {
        uploadPreset: "wo4qwffs",
        apiKey: "754134295584927",
        cloudName: "imostate",
      },
      progress: 0,
      start: false,
    };
  },
  mounted() {
    this.getDetails();
  },
  methods: {
    submit() {
        let tutor = JSON.parse(localStorage.getItem('typeTutor'))
      axios.put(`/api/tutors/${tutor.id}`, this.details,{headers:{
          Authorization: `Bearer ${tutor.access_token}`
      }}).then(res => {
        if (res.status == 200) {
          this.$toasted.info("Successful, changes will take effect on your next login!!");
         
        }
      });
    },
     handleFileChange(event) {
      this.file = event.target.files[0];

      this.filesSelectedLength = event.target.files.length;

      this.loadFile();
    },
    loadFile() {
      let reader = new FileReader();
      reader.onload = (event) => {
        this.uploadedFile = event.target.result;
      };
      reader.readAsDataURL(this.file);
      this.processUpload();
    },
    processUpload() {
      let that = this;
      this.start = true;
      var formData = new FormData();
      var xhr = new XMLHttpRequest();
      var cloudName = this.cloudinary.cloudName;
      var upload_preset = this.cloudinary.uploadPreset;
      formData.append("file", this.file);
      formData.append("resource_type", "auto");
      formData.append("upload_preset", upload_preset); // REQUIRED
      xhr.open(
        "POST",
        "https://api.cloudinary.com/v1_1/" + cloudName + "/upload"
      );
      xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
          that.progress = Math.round((e.loaded / e.total) * 100) + "%";
        }
      };
      xhr.upload.onloadstart = function (e) {
        this.progress = "Starting...";
      };
      xhr.upload.onloadend = function (e) {
        this.progress = "Completing..";
      };
      xhr.onload = (progressEvent) => {
        if (xhr.status === 200) {
          // Success! You probably want to save the URL somewhere
          this.progress = "Completed";
          setTimeout(() => {}, 1000);
          var response = JSON.parse(xhr.response);
          this.$toasted.info("Profile picture changed");
          this.details.profile = response.secure_url; // https address of uploaded file
        } else {
          this.start = false;
          this.progress = 0;
          alert("Upload failed. Please try again.");
        }
      };
      xhr.send(formData);
    },
    getDetails() {
      axios
        .get("/api/tutorDetails", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.details = res.data;
          }
        });
    },
    update() {
      this.$router.push(`/tutor/update/${this.details.id}`);
    },
  },
};
</script>

<style scoped>
.info {
  width: 80%;
  margin: 0 auto;
}
.image {
  width: 140px;
  height: 140px;
  border-radius: 50%;
  overflow: hidden;
}
.image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
ul {
  list-style: none;
}
li {
  text-transform: capitalize;
  padding: 10px 0;
}
.profile {
  /* display: flex;
  justify-content: center; */
  padding-top: 50px;
}
.avatar-profile {
  position: absolute;
  left: calc(50% - 35px);
transform: translateY(-65%);
}
.tutor-profile {
  background: #fff;
  position: relative;
  padding: 30px;
}
.personal-form {
  border: 1px solid #c4c4c4;
  margin-bottom: 30px;
  margin-top: 20px;
  padding:10px;
}
.save-btn{
  display: flex;
  justify-content: center;
  font-family: "Montserrat";
}
.btn-save{
  background: #0A4065;
  color: #fff;
  padding: 9px 16px;
  font-size: 16px;
  font-weight: 500;
}
.form-group{
  position: relative;
}
.after-edit:hover::before{
  content: "\f040";
  font-family: "FontAwesome";
  font-weight: 500;
  position: absolute;
  right: 0;
  top:0 ;
  transform: translateY(10px);
}
@media(max-width:425px){
  form{
  padding:10px
}
.avatar-profile {
    position: absolute;
    left: calc(50% - 35px);
    transform: translateY(-115%);
}
.tutor-profile{
  padding-top: 60px;;
}
}
</style>