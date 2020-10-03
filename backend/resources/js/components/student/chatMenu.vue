<template>
  <b-col md="4" class="p-0 pl-sm-0">
    <b-card class="online-presence  body-card">
      <div class="online-presence-top mb-4">
        <b-form-input placeholder="Search to start chat" v-model="search"></b-form-input>
        <span class="px-2">
          <b-icon icon="three-dots-vertical" id="popover-button-event" size="1.2rem"></b-icon>
        </span>
        <b-popover ref="popover" target="popover-button-event">
          <ul class="pop">
            <li @click="online">Who's Online?</li>
          </ul>
        </b-popover>
      </div>
    

      <div class="online-tag">
        <div @click="handleToggles('groups')" class="mb-4 d-flex justify-content-between align-items-center">
          <strong class="text-muted"><b-avatar text="G" class="mr-2" size="1.3rem"></b-avatar> Groups</strong>
          <b-icon icon="chevron-down" v-if="!toggleGroups" @click="handleToggles('groups')"></b-icon>
          <b-icon v-else  icon="chevron-up"></b-icon>
        </div>
        <div v-if="toggleGroups" class="mb-4">
          <div
            class="single-online-tag"
            v-for="(group,idx) in sortedGroups"
            :key="idx"
            @click="switchGroup('group',group.id,group)"
            :class="{active:group.name==active}"
          >
            <div class="inner-single">
              <div class="message-info">
                <b-avatar class="mr-3"></b-avatar>
                <div class="toCaps name">{{group.name}}</div>
              </div>
              <div>
                <!-- <b-badge variant="success">3</b-badge> -->
              </div>
            </div>
          </div>
        </div>
      
        <div  @click="handleToggles('tutors')" class="mb-4 d-flex justify-content-between align-items-center">
          <strong class="text-muted"><b-avatar class="mr-2" size="1.3rem" text="T"></b-avatar> Tutors</strong>
          <b-icon icon="chevron-down" v-if="!toggleTutors" @click="handleToggles('tutors')"></b-icon>
          <b-icon v-else icon="chevron-up"></b-icon>
        </div>
        <div v-if="toggleTutors" class="mb-4">
          <div
            class="single-online-tag"
            v-for="(tutor) in sortedTutors"
            :key="tutor.id"
            @click="switchGroup('tutorstudent',tutor.id,tutor)"
            :class="{active:tutor.name==active}"
          >
            <div class="inner-single">
              <div class="message-info">
                <b-avatar :src="tutor.profile" class="mr-3"></b-avatar>
                <div>
                  <p class="toCaps mb-1 name">{{tutor.name}}</p>
                
                  <small
                    v-if="sortedMessages(tutor.id).length"
                  >{{sortedMessages(tutor.id)[sortedMessages(tutor.id).length - 1].message}}</small>
                </div>
              </div>
              <div>
                <!-- <b-badge variant="success">3</b-badge> -->
                <small
                  v-if="sortedMessages(tutor.id).length"
                >{{sortedMessages(tutor.id)[sortedMessages(tutor.id).length - 1].created_at | moment('h:mm A')}}</small>
              </div>
            </div>
          </div>
        </div>
   
        <div class=" d-flex justify-content-between align-items-center mb-4" @click="handleToggles('mates')">
          <strong class="text-muted"><b-avatar text="C" class="mr-2" size="1.3rem"></b-avatar> Classmates</strong>
          <b-icon icon="chevron-down" v-if="!toggleClassmates" @click="handleToggles('mates')"></b-icon>
          <b-icon  v-else icon="chevron-up"></b-icon>
        </div>
        <div v-if="toggleClassmates"  class="mb-4">
          <div
            class="single-online-tag border-bottom"
            @click="switchGroup('private',mate.id, mate)"
            v-for="(mate) in sortedMates"
            :class="{active:mate.name==active}"
            :key="mate.id"
          >
            <div class="inner-single">
              <div class="message-info">
                <div class="mr-3">
                  <b-avatar :src="mate.profile"></b-avatar>
                </div>
                <div>
                  <p class="toCaps mb-1 name">{{mate.name}}</p>
                 
                  <small
                    class="lastmessage"
                    v-if="getLastMessage(mate.id).length"
                  >{{getLastMessage(mate.id)[getLastMessage(mate.id).length-1].message}}</small>
                </div>
              </div>
              <div>
                <small
                  v-if="getLastMessage(mate.id).length"
                >{{getLastMessage(mate.id)[getLastMessage(mate.id).length-1].created_at | moment('h:mm A')}}</small>
                <!-- <b-badge variant="success">3</b-badge> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </b-card>
  </b-col>
</template>
<script>
export default {
  props: [
    "student",
    "groups",
    "classmates",
    "receiver_id",
    "privateMessages",
    "studtutMessages",
  ],
  data() {
    return {
      search:'',
      active: "",
      tutors: [],
      toggleClassmates: true,
      toggleGroups: true,
      toggleTutors: false,
    };
  },
  mounted() {
    if (window.innerWidth >900) {
      setTimeout(() => {
      this.highlight();
    }, 1000);
    }
    this.getTutors();
  },
  computed: {
    sortedGroups(){
   return this.groups.filter((item) =>
        item.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    sortedTutors() {
      return this.tutors.filter((item) =>
        item.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    sortedMates() {
      return this.classmates.filter((item) =>
        item.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
   
  },
  methods: {
    handleToggles(val) {
      switch (val) {
        case "groups":
          this.toggleGroups = !this.toggleGroups;
          break;
        case "mates":
          this.toggleClassmates = !this.toggleClassmates;
          break;
        case "tutors":
          this.toggleTutors = !this.toggleTutors;
          break;

        default:
          break;
      }
    },
    sortedMessages(id) {
      return this.$props.studtutMessages.filter((item) => {
        return (
          (item.receiver_id == this.$props.student.id && item.user_id == id) ||
          (item.receiver_id == id && item.user_id == this.$props.student.id)
        );
      });
    },
    switchGroup(name, id, val) {
     
      this.$emit("switchGroup", name, id, val);
      this.active = val.name;
      this.$emit('toggleChat','body')
     
    },
    online() {
      this.$emit("online");
      this.$refs.popover.$emit("close");
    },
    highlight() {
       
     if(this.$props.classmates.length){
        this.active = this.$props.classmates[0];
     if (window.innerWidth > 768) {
       
        this.switchGroup( "private", this.$props.classmates[0].id, this.$props.classmates[0]
       
      );
      
     }
     }
    },
    getLastMessage(id) {
      return this.$props.privateMessages.filter(
        (item) => (item.receiver_id == id) | (item.user_id == id)
      );
    },
    getTutors() {
      axios
        .get("/api/get-tutors", {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.tutors = res.data;
          }
        });
    },
  },
};
</script>
<style scoped>
.view {
  background: white;
  position: relative;
  display: flex;
  height: 84vh;
}
.pop {
  margin: 0;
}
.name{
  font-size: 14px;
  font-weight: bold;;
}
.pop li {
  padding: 10px 0;
}
.single-online-tag {
  padding: 8px 10px;
   border-bottom:1px solid #ccc;
}
.single-online-tag.active {
  background-color: #fff;
  border-left: 3px solid #ccc;
 
}
.online-presence {
  background: #f5f4f4;
  height: 85vh;
}
.online-presence-top {
  display: flex;
  align-items: center;
}
.online-presence-top .fa {
  padding-left: 10px;
  padding-top: 10px;
}
.inner-single {
  display: flex;
  justify-content: space-between;
}
.message-info {
  display: flex;

  align-items: center;
}
/* .message-info h6 {
  margin-bottom: 0;
  line-height: 0;
} */
.file-attachement {
  display: flex;
  width: 100%;
  position: relative;
}
.file-attachement span {
  position: absolute;
  right: 0;
  transform: translate(-7px);
  padding-top: 5px;
}
.progress {
  height: 15px;
}
label {
  margin: 0 !important;
  display: block;
}
.message {
  font-size: 14px;
  padding: 20px;
}
.chat-body {
  width: 100%;
  height: 100%;
  padding: 20px 0 60px;
  position: relative;
  overflow: auto;
}
.online {
  height: 100%;
  width: 20%;
}
.chat-area {
  position: relative;
}
.send-tab {
  position: absolute;
  bottom: 0;
  background: #cec9c9;
  display: flex;
  width: 100%;
  padding: 10px;
}

.emoji {
  position: absolute;
  bottom: 100%;
  overflow: hidden !important;
}
.online ul li {
  font-size: 15px;
  padding: 10px 5px;
}
.form-group {
  position: relative;
}

ul,
ol {
  list-style: none;
}
.chat-message {
  background: rgba(255, 247, 237, 0.68);
  padding: 15px 20px;
  width: fit-content;
  width: max-content;
  max-width: 50%;

  text-align: left;
}
strong {
  font-size: 14px;
}
.online-tag {
  max-height: 67vh;
  overflow: auto;
}
@media screen and (max-width: 500px){
.message-info{
  padding-left: 10px;
}
.send-tab{
  position: fixed;
}
.online-presence{
  height:91vh;
}
}
</style>