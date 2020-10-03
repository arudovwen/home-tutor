<template>
  <div class="container">
    <b-form @submit.prevent="register" class="mx-auto register-right">
      <!-- Student starts here   -->

      <legend class="text-center">Register Student</legend>
      <b-form-group label="Category" v-if="admin">
        <b-form-select v-model="student.level"   class="toCaps">
          <b-form-select-option
          
            :value="level"
            v-for="(level,idx) in JSON.parse(admin.level)"
            :key="idx"
          >{{level}}</b-form-select-option>
        </b-form-select>
      </b-form-group>
      <div class="form-group">
        <input
          required
          type="text"
          class="form-control"
          placeholder="Full Name *"
          v-model="student.name"
        />
      </div>

      <div class="form-group">
        <div class="maxl">
          <label class="radio inline">
            <input required type="radio" value="male" checked v-model="student.gender" />
            <span>Male</span>
          </label>
          <label class="radio inline">
            <input required type="radio" value="female" v-model="student.gender" />
            <span>Female</span>
          </label>
        </div>
      </div>
      <div class="form-group">
        <input
          required
          type="email"
          class="form-control"
          placeholder="Your Email *"
          v-model="student.email"
        />
      </div>
      <b-form-group label="Faculty" v-if="JSON.parse(admin.level).includes('tertiary')">
        <b-form-select v-model="myfaculty">
          <b-form-select-option :value="null">Select</b-form-select-option>
          <b-form-select-option
            :value="faculty"
            v-for="(faculty,idx)  in faculties"
            :key="idx"
          >{{faculty.name}}</b-form-select-option>
        </b-form-select>
      </b-form-group>

      <b-form-group label="Department" v-if="JSON.parse(admin.level).includes('tertiary')">
        <b-form-select v-model="mycourse_id" v-if="mydepartments.length">
          <b-form-select-option :value="null">Select</b-form-select-option>
          <b-form-select-option
            :value="department"
            v-for="(department,idx)  in mydepartments"
            :key="idx"
          >{{department.name}}</b-form-select-option>
        </b-form-select>
      </b-form-group>
      <b-form-group label="Programme" v-if="JSON.parse(admin.level).includes('tertiary')">
        <b-form-select v-model="student.study_course" v-if="sortCourse.length">
          <b-form-select-option value>Select</b-form-select-option>
          <b-form-select-option
            :value="course.name"
            v-for="(course,idx)  in sortCourse[0].courses"
            :key="idx"
          >{{course.name}}</b-form-select-option>
        </b-form-select>
      </b-form-group>
      <b-form-group label="Choose Class Level">
      
        <b-form-select class="custom-select" v-model="student.student_level">
          <b-form-select-option selected value>Select Class</b-form-select-option>
          <b-form-select-option
            :value="item.class_name.toLowerCase()"
            v-for="(item,idx) in allClass"
            :key="idx"
          >{{item.class_name}}</b-form-select-option>
        </b-form-select>
      </b-form-group>
       <b-form-group label="Choose Arm" v-if="!JSON.parse(admin.level).includes('tertiary')">
      
        <b-form-select class="custom-select" v-model="student.arm">
          <b-form-select-option selected value>Select arm</b-form-select-option>
          <b-form-select-option
            :value="item.toLowerCase()"
            v-for="(item,idx) in sortedClass"
            :key="idx"
          >{{item}}</b-form-select-option>
        </b-form-select>
      </b-form-group>

      <b-form-group class="mb-3">
        <b-row>
          <b-col>
            <b-button variant="outline-secondary" @click="toggleModal" block type="button">Cancel</b-button>
          </b-col>
          <b-col>
            <b-button variant="success" block type="submit">Register</b-button>
          </b-col>
        </b-row>
      </b-form-group>
    </b-form>
  </div>
</template>


<script>
export default {
  data() {
    return {
      faculties: [],
      departments: [],
      admin: {},
      student: {
        name: "",
        email: "",
        gender: "male",
        student_level: "",
        level: "",
        study_course: null,
        faculty:null,
        department:null,
        arm:''
      },
      spin: false,
      allClass: [],
      myfaculty: {},
      mycourse_id: {},
      courses: [],
    };
  },
  created() {
    this.admin = JSON.parse(localStorage.getItem("typeAdmin"));
    this.student.level = JSON.parse(this.admin.level)[0]
  },
  mounted() {
    this.getCLasses();
    this.getFaculties();
    this.getDepartment();
    this.getCourses();
  },
  computed: {
     sortedClass() {
      var sub = [];
      this.allClass.forEach((item) => {
        if (item.class_name.toLowerCase() == this.student.student_level.toLowerCase()) {
          if (item.sub_class !== "") {
            item.sub_class.split(",").forEach((i) => {
              sub.push(i);
            });
          }
          return sub;
        }
        return sub;
      });
      return sub;
    },
    mydepartments() {
      return this.departments.filter(
        (item) => item.faculty_id == this.myfaculty.id
      );
    },
    sortCourse() {
      return this.mydepartments.filter((item) => item.id == this.mycourse_id.id);
    },
  },
  methods: {
    getCourses() {
      axios
        .get(`/api/course`, {
          headers: {
            Authorization: `Bearer ${this.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.courses = res.data;
          }
        });
    },
    getFaculties() {
      axios
        .get(`/api/faculty`, {
          headers: {
            Authorization: `Bearer ${this.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.faculties = res.data;
            this.myfaculty = res.data[0];
          }
        });
    },
    getDepartment() {
      axios
        .get(`/api/department`, {
          headers: {
            Authorization: `Bearer ${this.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.departments = res.data;
          }
        });
    },
    toggleModal() {
      this.$emit("toggleModal", "student-create");
    },
    register() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      if (JSON.parse(admin.level).includes('tertiary')) {
         this.student.faculty = this.myfaculty.name
      this.student.department = this.mycourse_id.name
      }
      axios
        .post("/api/register", this.student, {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 201) {
            this.$toasted.info("Successful");
            this.toggleModal();
          }
        });
    },
    getCLasses() {
      let admin = JSON.parse(localStorage.getItem("typeAdmin"));
      axios
        .get("/api/classes", {
          headers: {
            Authorization: `Bearer ${admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allClass = res.data;
          }
        });
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
}
form {
  width: 100%;
}
.form-group {
  margin-bottom: 24px;
}
</style>