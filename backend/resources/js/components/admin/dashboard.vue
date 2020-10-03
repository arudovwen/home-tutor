<template>
  <div class="my-container d-flex">
    <Side class="side" :sideBar="sideBar" @minimise="minimise" :class="{'min-side':sideBar}" />
    <Main class="main" :class="{'min-main':sideBar}" :onlineTutors="onlineTutors"/>
  </div>
</template>


<script>
import Side from "./sideBar";
import Main from "./main";
export default {
  data() {
    return {
      sideBar: false,
      onlineTutors: [],
    };
  },
  components: {
    Main,
    Side,
  },
  created() {
    Echo.join("online")
      .listen("OnlineTutors", (e) => {
      
      })
      .here((users) => {
      
        users.forEach(item=>{
           this.onlineTutors.push(item.name)
        })
      
      })
      .joining((user) => {
       
        this.onlineTutors.push(user.name);
      })
      .leaving((user) => {
    
        this.onlineTutors = this.onlineTutors.filter((u) => u !== user.name);
      
      });
  },
  methods: {
    minimise() {
      this.sideBar = !this.sideBar;
    },
  },
};
</script>

<style scoped>
.my-container {
  width: 100%;
  display: flex;
}
.side {
  width: 15%;
  height: 100vh;
  background: #008e3a;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  position: fixed;
}
.main {
  width: 85%;
  min-height: 100%;
  position: absolute;
  right: 0;
  background: #f7f8fa;
  padding-bottom: 30px;
}
.min-side {
  width: 4% !important;
}
.min-main {
  width: 96% !important;
  overflow: auto;
}
@media screen and (max-width: 900px){
  .side{
    display: none;
  }
  .main{
    width: 100%;
    position: initial;
    overflow: scroll;
   
  }
}
</style>