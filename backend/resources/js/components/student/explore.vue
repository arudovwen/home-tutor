<template>
  <div class="view">
    <!-- <h2 class="mb-3 ">Explore Resources</h2> -->

    <ul class="filter-list mb-5">
      <li @click="selectFilter('')" class="cpointer mr-4">All</li>

      <li
        v-for="(item,idx) in subjects"
        :key="idx"
        @click="selectFilter(item.name)"
        class="toCaps cpointer mr-4"
      >{{item.name}}</li>
    </ul>

    <div class="container-fluid explore-content" v-if="subjects.length">
      <b-row  class="tb" v-if="filteredSubjects.length">
        <b-col cols="12" sm="3"   v-for="(item,idx) in filteredSubjects"
            :key="idx">
          <b-card
          
            :title="item.subject"
            :img-src="item.cover"
            img-alt="Image"
            img-top
           
            style="max-width: 25rem"
            class="mb-4"
          >
            <b-card-text class="excerpt">{{item.description}}</b-card-text>         
            <b-card-text>{{item.grade_level}}</b-card-text>
            <small class="update mb-1">Last update {{item.created_at | moment('MMMM D')}}</small> <br>
            <b-button size="sm" @click="gotoHer(item.id)">Visit resource</b-button>
          </b-card>
        </b-col>
      </b-row>
      <p v-else class="mx-auto text-center">No available resource yet !</p>
    </div>
    <b-alert show v-else>Register {{student.course_level=='tertiary'?'Courses':'Department'}} to view Resources !</b-alert>
  </div>
</template>

<script>
export default {
  props: ["student"],
  data() {
    return {
      resources: [],
      opened: [],
      current: "",
      subjects: [],
      filter: "",
      fill: { gradient: ["red", "green", "blue"] },
    };
  },
  mounted() {
    // this.getResources();
    // this.getSubjects();
    this.getSyl();
    this.getSub()
  },
  computed: {
    filteredSubjects() {
      return this.resources.filter((item) => {
        return item.subject.toLowerCase().includes(this.filter.toLowerCase());
      });
    },
 
  },
  methods: {
    getSyl() {
      axios
        .get(`/api/student-syllabus`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.resources = res.data;
          }
        });
    },
    selectFilter(name) {
      this.filter = name;
    },
    gotoHer(id) {
      this.$router.push(`/student/resource/view/${id}`);
    },
    addtolibrary(id) {
      let data = {
        id: this.resources[id].id,
        subject: this.resources[id].subject,
        title: this.resources[id].module,
        content: this.resources[id].content,
        worksheet_id: this.resources[id].worksheet_id,
      };
      axios
        .post("/api/library", data, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.success("Added to Library", {
              icon: {
                name: "check",
                after: false,
              },
            });
          } else {
            this.$toasted.error("Already in Library", {
              icon: "ban",
              after: false,
            });
          }
        });
    },
    checkDuplicateInObject(propertyName, inputArray) {
      var testObject = {};
      var newArr = [];
      inputArray.map(function (item) {
        var itemPropertyName = item[propertyName];

        if (Object.values(testObject).includes(itemPropertyName)) {
        } else {
          testObject = item;
          newArr.push(item);
        }
      });
      return newArr;
    },
    // getResources() {
    //   axios.get(`/api/get-modules/${this.$props.student.level}/${this.$props.student.school_id}`, {
    //       headers: {
    //         Authorization: `Bearer ${this.$props.student.access_token}`,
    //       },
    //     }).then((res) => {
    //     if (res.status == 200) {

    //       this.resources = this.checkDuplicateInObject(
    //         "subject",
    //         res.data.data
    //       );

    //     }
    //   });
    // },
    // getSubjects() {
    //   axios
    //     .get("/api/student-all-subjects", {
    //       headers: {
    //         Authorization: `Bearer ${this.$props.student.access_token}`,
    //       },
    //     })
    //     .then((res) => {
    //       if ((res.status = 200)) {
    //         this.subjects = res.data;
    //       }
    //     });
    // },
    getSub() {
      axios
        .get(`/api/getsub`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if ((res.status = 200)) {
            this.subjects = res.data;
          }
        });
    },
    view(id) {
      this.current = id;
      if (this.opened.includes(id)) {
        let ele = this.opened.indexOf(id);

        this.opened.splice(ele);
      } else {
        this.opened.push(id);
      }
    },
  },
};
</script>

<style scoped>
.view {
  padding: 40px 20px 60px;
}
.main-content {
  width: 80%;
  margin: 0 auto;
}
.title {
  font-size: 13px;
  font-weight: bold;
  margin-bottom: 3px;
}
.align-bg {
  display: flex;
  justify-content: space-between;
}
h4.card-title{
  font-size:13px;
  text-transform: capitalize;
}
p,h4{
  margin-bottom: 3px;
  font-size: 13px;
}
.desc {
  height: 42px;
  font-size: 13px;
  overflow: hidden;
  display: -webkit-box !important;
  -webkit-line-clamp: 2;
  -moz-line-clamp: 2;
  -ms-line-clamp: 2;
  -o-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  -ms-box-orient: vertical;
  -o-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
  margin-bottom: 10px;
}
.top_box {
  background: #f7f8fa;
}
a {
  text-decoration: none;
  color: black;
}
ul,
ol {
  list-style: none;
}
ul.breadcrumb li + li:before {
  padding: 8px;
  color: black;
  content: "/\00a0";
}

ul.breadcrumb li + li:before:last-child {
  content: "";
}
.filter-list {
  display: flex;
  justify-content: flex-start;
  font-size: 14px;
  border-bottom: 1px solid #ccc;
  font-weight: bold;
}
.filter-list li {
  color: rgba(0, 0, 0, 0.54);
  border-bottom: 2px solid transparent;
  padding: 8px 0;
}
.filter-list li:hover {
  color: rgba(0, 0, 0, 0.84);
  border-color: rgba(0, 0, 0, 0.84);
}
img{
  width:100%;
  height:130px;
}
.hover-shadow:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.single-content {
  position: relative;
  transition: 0.3s;
  box-shadow: 10px 20px 20px rgba(247, 248, 250, 0.8);
}
.single-content .text-content,
.single-content::after {
  position: absolute;
  left: 10px;
  right: 10px;
}

.single-content::after {
  content: "";
  display: block;
  background: #13a699;
  top: 20px;
  bottom: 20px;
  opacity: 0;
  transform: rotate3d(-1, 1, 0, 100deg);
  transition: 0.4s;
}
.resource-btn {
  display: flex;
  justify-content: center;
  font-family: "Montserrat";
  padding-top: 10px;
}
.resource-btn .btn {
  background: #13a699;
  color: #fff;
  border: none;
}
.single-content:hover::after {
  opacity: 0.9;
  transform: rotate3d(0, 0, 0, 0deg);
}
.single-content img {
  width: 100%;
  height: 100%;
}

.text-content {
  top: 45%;
  opacity: 0;
  z-index: 1;
  transform: translate(10%, -30%);
  transition: 0.3s;
  text-align: center;
  color: #fff;
  margin-top: 5px;
}
.text-content .btn {
  color: #fff !important;
  border: 1px solid #fff;
  border-radius: 5px;
  padding: 10px 20px;
}
.single-content:hover .text-content {
  opacity: 1;
  transform: translate(0, -50%);
  transition-delay: 0.3s;
}
.explore-content {
  margin-top: 15px;
}
/* .router-link-active{
  border-bottom: 2px solid #ffd708;
} */
div {
  font-family: "Montserrat";
}
.excerpt {
  height: 42px;
  font-size: 13px;
  overflow: hidden;
  display: -webkit-box !important;
  -webkit-line-clamp: 2;
  -moz-line-clamp: 2;
  -ms-line-clamp: 2;
  -o-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  -moz-box-orient: vertical;
  -ms-box-orient: vertical;
  -o-box-orient: vertical;
  box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: normal;
}
.update {
  background: rgba(196, 196, 196, 0.15);
  padding: 5px;
  border-radius: 5px;
}
.card-body{
  padding:10px 10px;
}
@media screen and (max-width: 900px) {
  .explore-card {
    margin-bottom: 15px;
  }
  .filter-list {
    max-width: 100%;
    overflow: auto;
    font-size: 11.5px;
  }
  .tb{
    justify-content: center;
  }
}
</style>