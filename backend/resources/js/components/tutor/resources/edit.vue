<template>
 
  <div class="add-resource">
    <p><router-link to="/tutor/resources">Go back</router-link></p>
    <h5 class="text-center py-3">Update Resource</h5>
    <b-container class="form-container">
      <b-form @submit.prevent="submit" v-if="resource">
        <b-form-row>
          <b-col md="4">
            <div class="form-group mb-3">
              <label for>Select Class</label>
              <br />
              <select class="custom-select" v-model="resource.level">
                <option selected value>Select Class</option>
                <option
                  :value="item.class_name.toLowerCase()"
                  v-for="(item,idx) in allClass"
                  class="toCaps"
                  :key="idx"
                >{{item.class_name}}</option>
              </select>
            </div>
          </b-col>
          <b-col md="4">
           <div class="form-group mb-3" v-if="sortedSubject.length">
              <label for>Select Subject</label>
              <br />
              <select
                class="custom-select toCaps"
                v-model="resource.subject"
                :disabled="resource.level ==''"
              >
                <option selected disabled value>Select one</option>
                <option
                  :value="item"
                  v-for="(item,idx) in JSON.parse(sortedSubject[0].classsubject.subjects)"
                  class="toCaps"
                  :key="idx"
                >{{item}}</option>
              </select>
            </div>
          </b-col>

         
          <b-col md="4">
            <div class="form-group mb-5">
              <label for>Select Module</label>
              <br />
              <select
                class="custom-select"
                v-model="resource.module"
              
              >
                <option selected disabled value>Select one</option>
                <option
                  :value="item.name"
                  v-for="(item,idx) in modules"
                  class="toCaps"
                  :key="idx"
                >{{item.name}}</option>
              </select>
            </div>
          </b-col>
        </b-form-row>
        <div class="form-group mb-5">
          <label for>Excerpt (optional)</label>
          <textarea class="form-control" rows="3" v-model="resource.excerpt"></textarea>
        </div>
        <b-form-group label="Resource Upload Type">
          <b-form-radio-group v-model="resource.count" :options="options" plain name="plain-inline"></b-form-radio-group>
        </b-form-group>
        <b-form-row v-if="resource.count == 'multiple'">
          <b-button type="button" @click="addmore" class="mr-4">
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
          </b-button>

          <b-button type="button" @click="addmore" v-if="resource">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
          </b-button>
        </b-form-row>
       <div>
          <div v-for="(item,idx) in resource.content" :key="idx">
          <div class="upload-resource container">
            <h5>Upload Resource {{idx + 1}}</h5>
            <b-form-row>
              <b-col md="6">
                <div class="form-group mb-3">
                  <label for>Choose Format</label>
                  <br />
                  <select class="custom-select" v-model="item.type">
                    <option selected disabled value>Select one</option>
                    <option value="pdf">PDF</option>
                    <option value="audio">Audio</option>
                    <option value="video">Video</option>
                    <option value="ppt">PPT</option>
                    <option value="csv">CSV</option>
                  </select>
                </div>
              </b-col>
              <b-col md="6">
                <div class="form-group">
                  <label for>Title</label>
                  <input
                    type="text"
                    class="form-control"
                    v-model="item.title"
                    aria-describedby="helpId"
                    placeholder
                  />
                </div>
              </b-col>
            </b-form-row>
            <div class="form-group">
              <h6>Overview (250 characters)</h6>
              <textarea class="form-control" v-model="item.overview" maxlength="250" rows="3"></textarea>
            </div>
            <b-form-group>
              <Upload :index="idx" @getUploadDetails="getUploadDetails" />
              <p class="toCaps animated fadeIn" v-if="item.type !==''">Resource Type : {{item.type}}</p>
              <p class="toCaps animated fadeIn" v-if="item.name !==''">Resource Name : {{item.name}}</p>
            </b-form-group>
          
          </div>
        </div>
       </div>
     
        <b-form-group class="my-5 w-25">
          <h5 class="mb-3">Upload Cover</h5>

          <label for="cover">
            <b-avatar :src="resource.cover_image" rounded size="7rem" icon="image-fill"></b-avatar>
          </label>
          <MiniUpload :label="label" :index="cover" @getUploadDetails="getUploadDetails" />
        </b-form-group>
        <!-- <b-form-group label="Would You Like to Include Worksheet/Quiz">
          <b-form-radio-group
            v-model="resource.worksheet"
            :options="worksheet"
            plain
            name="plain-inline"
          ></b-form-radio-group>
        </b-form-group> -->
        <div class="form-group mb-5">
          <label for>Tutors Note</label>
          <textarea class="form-control" rows="3" v-model="resource.note"></textarea>
        </div>

        <div class="save-btn">
          <b-button type="submit" class="btn-save">UPDATE RESOURCE</b-button>
        </div>
      </b-form>
    </b-container>
  </div>
</template>

<script>

import Upload from "../../uploadComponent";
import MiniUpload from "../../miniupload";
export default {
  props: ["tutor"],
  data() {
    return {
      selected: "single",
      options: [
        { text: "Single Resource", value: "single" },
        { text: "Multiple Resource", value: "multiple" },
      ],
      selected: "yes",
      worksheet: [
        { text: "Yes", value: "yes" },
        { text: "No", value: "no" },
      ],
      label: "cover",
      cover: "cover",
      allClass: [],
      modules: [],
      subjects: [],

      resource: {
        level: "",
        subject: "",
        module: "",
        excerpt: "",
        count: "single",
        cover_image: "",
        type:"new resource",
        content: [
          {
            type: "",
            file: "",
            name: "",
            title: "",
            overview: "",
            duration:''
          },
        ],
        worksheet: "no",
        note: "",
      },
    };
  },

  components: {
    Upload,
    MiniUpload,
  },
 computed: {
     sortedSubject() {
      var subjects = this.allClass.filter((item) =>
        item.class_name
          .toLowerCase()
          .includes(this.resource.level.toLowerCase())
      );

      return subjects;
    },
  },
created() {
  this.getResource();
},
  mounted() {
   
    this.getCLasses();
    this.getSubjects();
    
  },
  // watch: {
  //   "resource.subject": "getModules"
  // },
  methods: {
    submit() {
      axios
        .put(`/api/resource/${this.$route.params.id}`, this.resource, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.$toasted.info("Updated successfully");
            this.$router.push("/tutor/resources");
          }
        });
    },
    getCLasses() {
      axios
        .get("/api/all-classes", {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
           
            this.allClass = res.data;
          }
        });
    },
    addmore() {
      this.resource.content.push({
        type: "",
        file: "",
        name: "",
         title: "",
            overview: ""
      });
    },
    getUploadDetails(index, res,id) {
      this.resource.content[index].file = res.secure_url;
      this.resource.content[index].name = res.original_filename;
         this.resource.content[index].duration = res.duration;

    },
    getModules(level,subject) {
      axios
        .get(
          `/api/tutor-module/${level}/${subject}`,
          {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`
            }
          }
        )
        .then(res => {
          if (res.status == 200) {
            this.modules = res.data;
          }
        });
    },
    getResource() {
      axios
        .get(`/api/edit-resource/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.resource = res.data;
             
             this.resource.content = JSON.parse(res.data.content);
              this.getModules(res.data.level.toLowerCase(),res.data.subject.toLowerCase())
          }
        });
    },
    getSubjects() {
      axios
        .get(`/api/tutor-all-subjects`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`
          }
        })
        .then(res => {
          if (res.status == 200) {
            this.subjects = res.data;
            
          }
        });
    }
  }
};
</script>

<style scoped>
.body {
  padding: 40px 20px 60px;
}
.add-resource {
  padding: 20px 30px;
}
.form-container {
  background: #fff;
  border: 3px solid #22cade;
  border-radius: 10px;
  box-shadow: 4px 4px 4px rgba(249, 247, 240, 0.25);
  padding: 10px 20px;
  margin-top: 10px;
}
.upload-resource {
  border: 1px dotted #22cade;
  padding: 10px 20px;
  margin-top: 10px;
}
.custom-select {
  display: inline-block !important;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 1.75rem 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #495057;
  vertical-align: middle;
  /* background: #fff; */
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  /* -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none; */
  background: #fff url(/images/down-chevron.png) no-repeat right 0.75rem
    center/8px 10px;
}
label {
  font-family: "Montserrat";
  font-weight: 500;
}
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
  width: 100%;
}
.upload-btn-wrapper .btn {
  border: 1px solid gray;
  color: gray;
  background-color: white;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
  border-style: dotted;
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.upload-btn-wrapper input[type="file"] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.save-btn {
  display: flex;
  justify-content: center;
}
.btn-save {
  background-color: #0a4065;
  padding: 10px 20px;
}
</style>