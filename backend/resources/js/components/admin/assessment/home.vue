<template>
  <div class="body">
    <b-container>
      <h4 class="mb-4">All Assessments</h4>
      <b-row class="my-3">
        <b-col md="2">
          <b-form-group :label="JSON.parse(admin.level).includes('tertiary')?'Course':'Subject'">
            <b-form-select size="sm" v-model="subject">
            <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option :value="item.name" v-for="(item,idx) in subjects"  :key="idx">
              {{item.name}}
            </b-form-select-option>
          </b-form-select>
          </b-form-group>
        </b-col>
        <b-col md="2">
          <b-form-group :label="JSON.parse(admin.level).includes('tertiary')?'Lecturer':'Teacher'">
             <b-form-select size="sm" v-model="tutor">
              <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option :value="item.id" v-for="(item,idx) in tutors"  :key="idx">
              {{item.name}}
            </b-form-select-option>
          </b-form-select>
          </b-form-group>
        </b-col>
        <b-col md="2">
          <b-form-group size="sm" :label="JSON.parse(admin.level).includes('tertiary')?'Level':'Class'">
             <b-form-select v-model="level">
              <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option :value="item.class_name" v-for="(item,idx) in levels"  :key="idx">
              {{item.class_name}}
            </b-form-select-option>
          </b-form-select>
          </b-form-group>
        </b-col>
         <b-col md="2">
          <b-form-group label="Format">
             <b-form-select size="sm" v-model="type">
              <b-form-select-option value="">Select</b-form-select-option>
            <b-form-select-option :value="item" v-for="(item,idx) in formats"  :key="idx">
              {{item}}
            </b-form-select-option>
          </b-form-select>
          </b-form-group>
        </b-col>
        <b-col md="4">
         <b-form-group label="Search ">
            <b-form-input size="sm" placeholder="Search" v-model="search"></b-form-input>
         </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
         <div class="assessment-table">
            <b-table :fields="fields" :items="sorted" v-if="sorted.length">
            <template v-slot:cell(action)="data">
               <div class :id="data.item.id.toString()">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>

                        <b-popover :target="data.item.id.toString()" triggers="hover" placement="top">
                         <ul>
                    <li>
                      <span
                         v-if="data.item.status=='declined' || data.item.status=='pending'"
                        @click="verify(data.item.id,'approved')"
                      
                      >Approve</span>
                      <span
                      v-if="data.item.status=='approved'"
                        @click="verify(data.item.id,'declined')"
                       
                      >Decline</span>
                    </li>
                    <li @click="view(data.item.id)">View</li>
                    <li @click="drop(data.item.id)">Drop</li>
                  </ul>
                        </b-popover>
                      </div>
             
            </template>
            <template v-slot:cell(created_at)="data">{{data.item.created_at | moment('MMM D YYYY')}}</template>
            <template v-slot:cell(Sn)="data">{{data.index+1}}</template>
             <template v-slot:cell(session)="data"><div class="toCaps">{{data.item.session}}</div></template>
               <template v-slot:cell(type)="data"><div class="toCaps">{{data.item.type}}</div></template>
          </b-table>
            <b-alert show v-else>No Data</b-alert>
         </div>
        </b-col>
      </b-row>
    </b-container>
    <b-modal id="preview" size="lg" hide-footer>
      <Preview :id="id" />
    </b-modal>
  </div>
</template>

<script>
import Preview from "./view";
export default {
  props:['admin'],
  data() {
    return {
      search: "",
      level: "",
      subject: "",
      tutor: "",
      type: "",
      subjects: [],
      tutors: [],
      levels: [],
      formats: ['Assignment','Exam','Test','Quiz'],
      id: null,
      fields: [
        "Sn",
        "session",
        { key: "subject", sortable: true },
        { key: "title", sortable: true },
        { key: "type", sortable: true },
        { key: "created_at", sortable: true },
        "level",
        { key: "status", sortable: true },
        "action",
      ],
      items: [],
    };
  },
  components: {
    Preview,
  },
  mounted() {
    this.getData();
    this.getSubjects();
    this.getTutors();
    this.getclasses();
  },
  computed: {
    sorted(){
      var search = this.items.filter(item=>item.title.toLowerCase().includes(this.search.toLowerCase()))
     
      var format = search.filter(item=>item.type.toLowerCase().includes(this.type.toLowerCase()))
      
       var subject = format.filter(item=>item.subject.toLowerCase().includes(this.subject.toLowerCase()))
       
        var level = subject.filter(item=>item.level.toLowerCase().includes(this.level.toLowerCase()))
        
         var tutor = level.filter(item=>item.tutor_id.toString().includes(this.tutor.toString()))
         return tutor;
        
    }
  },
  methods: {
    getSubjects() {
      axios
        .get("/api/subject", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        });
    },
    getTutors() {
      axios
        .get("/api/tutor", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
          }
        });
    },
    getclasses() {
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.levels = res.data;
          }
        });
    },
   
   
   getData() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get(`/api/admin-assessments`, {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.items = res.data;
          }
        })
        .catch();
    },
    drop(id) {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/admin-drop-assessment/${id}`, {
            headers: {
              Authorization: `Bearer ${admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getData();
            }
          });
      }
    },
    verify(id, name) {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      let del = confirm("Are you sure?");
      if (del) {
        let data = {
          status: name,
        };
        axios
          .put(`/api/admin-verify-assessment/${id}`, data, {
            headers: {
              Authorization: `Bearer ${admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getData();
            }
          });
      }
    },
    view(id) {
      this.id = id;
      this.$bvModal.show("preview");
    },
  },
};
</script>

<style scoped>
.form-group{
  margin:15px 0;
}
.assessment-table{
  overflow: scroll;
}
ul{
  list-style: none;
}
@media screen and (max-width: 600px) {
 
  legend{
    font-size:14px !important;
  }
}
</style>