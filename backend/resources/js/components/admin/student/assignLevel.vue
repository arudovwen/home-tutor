<template>
  <div class="body">
    <b-row class="mb-5  top-side border p-3">
      <b-col cols="12">
        <h6 class="mb-3">Choose Class</h6>
        <div class="top_box">
       
           <div class="search-box">
             <b-form-group>
               <b-form-input v-model="class_search" placeholder="Search class" size="sm"></b-form-input>
             </b-form-group>
             <div class="box" v-if="class_search!==''">
               <b-list-group>
                 <b-list-group-item v-for="(item,idx)  in searchC" :active="current==item.class_name" :key="idx" @click="selectClass(item.class_name)">
                   {{item.class_name}}
                 </b-list-group-item>
               </b-list-group>
             </div>
           </div>
        </div>
      </b-col>

      <b-col cols="12" class="mt-3" v-if="!JSON.parse(admin.level).includes('tertiary')"> 
        <h6 class="mb-3">Choose Arm</h6>
        <div class=" top_box">
         
            <b-list-group>
                 <b-list-group-item v-for="(item,idx)  in sortedClass" :active="activearm==item"  :key="idx" @click="chooseSubClass(item)">
                   {{item}}
                 </b-list-group-item>
               </b-list-group>
        </div>
      </b-col>
    </b-row>

    <div class="border p-3" v-if="mySubClass">
      <h6 class="text-center mb-3">
        Student
        <i class="fa fa-arrows-h mx-2" aria-hidden="true"></i> Class
      </h6>
      <div class="">
        <div class="">
          <div class="top_box">
           
            
            <b-row >
              <b-col cols="6" sm="4" v-for="(item,idx)  in !JSON.parse(admin.level).includes('tertiary')?sortedStudents:students"  :key="idx" >
                <b-form-group>
                  <b-form-checkbox v-model="mystudents" :value="item.name">{{item.name}}</b-form-checkbox>
                </b-form-group>
              </b-col>
            </b-row>
          
          </div>
        </div>
          
            <div class="text-center my-3">
              <b-button type="button" @click="submit" size="sm">Register</b-button>
            </div>
         
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["admin"],
  data() {
    return {
      class_search:'',
      students: [],
      allClass: [],
      mySubClass: "",
      current: "",
      activearm:'',
      data: [
        {
          my_class: "",
          students: [],
        },
      ],
      mystudents:[],
      field1: ["class_list"],
      field2: ["student_list"],
      sub_class: [],
    };
  },
  mounted() {
    this.getStudents();
    this.getCLasses();
  },
  computed: {
    searchC(){
       return this.allClass.filter(item=>item.class_name.toLowerCase().includes(this.class_search.toLowerCase()))
    },
    sortedClass() {
      var sub = [];
      this.allClass.forEach((item) => {
        if (item.class_name == this.current) {
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
    sortedStudents() {
      return this.students.filter((item) => {
        if (item.student_level.toLowerCase() == this.current.toLowerCase()) {
          return item;
        }
      });
    },
  },
  methods: {
    removeName(index, id) {
      this.data[index].students.splice(id, 1);
    },
    toggleModal() {
      this.$emit("toggleModal", "student-create");
    },
    submit() {
    
        let admin = JSON.parse(localStorage.getItem("typeAdmin"));
        let data = {
          data: this.mystudents,
          level: this.current,
          subclass: this.mySubClass,
        };
        axios
          .post("/api/class-student", data, {
            headers: {
              Authorization: `Bearer ${admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.$toasted.info("Successful");
              this.toggleModal();
              this.data = []
               this.data.push({
                  my_class: "",
                  students: []
                })
              
            }
          });
     
    },
    getSubclass() {
      this.sub_class = [];
      this.allClass.forEach((item) => {
        if (item.class_name == this.current) {
          // if (item.sub_class !== "") {
          item.sub_class.split(",").forEach((i) => {
            this.sub_class.push(i);
          });
          // }
        }
      });
    },
    chooseSubClass(name) {
     
      this.activearm = name
      this.mySubClass = name;   
         
 
     
    },
    selectClass(name) {
      this.current = name;
      if (JSON.parse(this.$props.admin.level).includes('tertiary')) {
        this.chooseSubClass(name)
      }
    },
    selectStudent(id, name) {
      let arr = [];
      let array = [];

      this.data.forEach((item) => {
        item.students.forEach((i) => {
          array.push(i.id);
        });
      });

      if (array.includes(id)) {
        this.$toasted.info("Added already");
      } else {
        this.data[this.data.length - 1].students.push({
          id: id,
          name: name,
        });
      }
    },
    getStudents() {
      axios
        .get("/api/admin-get-students", {
          headers: {
            Authorization: `Bearer ${this.$props.admin.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.students = res.data;
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
    save() {
      this.data.push({
        my_class: "",
        students: [],
      });
    },
    remove() {
      this.data.pop();
    },
  },
};
</script>
<style scoped>
.body {
  min-height: 100%;
  padding: 40px 20px 70px;
}
.bottom_box {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  grid-column-gap: 20px;
}
.t-body {
  max-height: 400px;
  overflow: scroll;
}
.selected {
  background: slategrey;
}
.top_box {
  display: block;
  position: relative;

}
.left-side {
  width: 40%;
  padding: 15px;
}
.right-side {
  width: 60%;
  padding: 15px;
}
.table .thead-light th {
  color: white;
  background-color: rgba(19, 166, 153, 0.9);
  border-color: rgba(19, 166, 153, 0.9);
}
.single-student {
  border: 1px solid #ccc;
  color: rgb(0, 0, 0, 0.74);
  padding: 11px;
}
.list-group-item{
  font-size:14px;
}
.list-group-item {
   
    padding: 0.45rem 1rem;
}
.box{
  max-height:150px;
  overflow:auto;
    border: 1px solid rgba(0, 0, 0, 0.125);
}
.top-head {
  color: white;
  background-color: rgba(19, 166, 153, 0.9);
  border-color: rgba(19, 166, 153, 0.9);
}
</style>