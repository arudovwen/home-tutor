<template>
  <div>
    <b-container fluid>
      <h4 class="mb-3">Todays Class</h4>
      <div class="d-flex justify-content-around mb-2">
        <span>
          <b-icon icon="circle-fill" class="upcoming-i mr-1"></b-icon>Upcoming
        </span>
        <span>
          <b-icon icon="circle-fill" class="ongoing-i mr-1"></b-icon>Ongoing
        </span>
        <span>
          <b-icon icon="circle-fill" class="finished-i mr-1"></b-icon>Finished
        </span>
      </div>
      <b-row class="p-3">
        <b-col cols="12" sm="4" md="3" class="p-2" v-for="(val,id) in liveC" :key="id">
          <b-card
            title="Class Detail"
            :class="{'ongoing-bg':( getSeconds(today) > getSeconds(val.start_time) && 
          getSeconds(today)<= getSeconds(val.end_time) && val.day.toLowerCase()== day), 
          'finished-bg':( getSeconds(today) > getSeconds(val.start_time) && 
          getSeconds(today)> getSeconds(val.end_time) && val.day.toLowerCase()== day),
          'upcoming-bg':( (getSeconds(today) < getSeconds(val.start_time)&& getSeconds(today) < getSeconds(val.end_time)))
          }"
            class="mb-2"
          >
            <b-card-text class="toCaps">Subject : {{val.subject}}</b-card-text>
            <b-card-text class="toCaps">Tutor : {{val.tutor}}</b-card-text>
            <b-card-text class="toCaps">Level : {{val.level}}</b-card-text>
            <b-card-text class="toLower">Start time : {{val.start_time | format }}</b-card-text>
            <b-card-text class="toLower">End time :{{val.end_time | format }}</b-card-text>
            <b-card-text>
              Class Link :
              <router-link to>{{val.link}}</router-link>
            </b-card-text>
            <b-card-text>Password: {{val.password}}</b-card-text>

            <div class="text-center">
              <b-button variant="success" @click="enterClass(val)" block><i class="fa fa-sign-in" aria-hidden="true"></i>  Enter Classroom</b-button>
            </div>
          </b-card>
        </b-col>
        <b-col v-if="!todaysClass">
          <b-alert show>No class Today</b-alert>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
export default {
  props: ["student"],
  data() {
    return {
      subject: "Mathematics",
      level: "Jss 1a",
      tutor: "Kerry",
      todaysClass: [],
      day: new Date()
        .toLocaleString("default", { weekday: "long" })
        .toLowerCase(),
      today:
        new Date().getHours() +
        ":" +
        new Date().getMinutes() +
        ":" +
        new Date().getSeconds(),
    };
  },
  mounted() {
    this.getLive();
  },
  computed: {
    liveC(){
      return this.todaysClass.filter(item=>{
        return item.level.toLowerCase().includes(this.$props.student.sub_class.toLowerCase())
      })
    }
  },
  methods: {
    enterClass(val) {
      
      let data = {
        school_id: this.$props.student.school_id,
        user_id: this.$props.student.id,
        tutor: val.tutor,

        subject: val.subject,
        day: new Date().toDateString(),
        record: "pending",
        level: val.level,
        time: new Date().toLocaleTimeString(),
      };
      
      if (
        this.getSeconds(this.today) > this.getSeconds(val.start_time) &&
        this.getSeconds(this.today) > this.getSeconds(val.end_time)
      ) {
        this.$toasted.info("This Class has ended");
      }
      if (
      (this.getSeconds(this.today) < this.getSeconds(val.start_time)&& this.getSeconds(this.today) < this.getSeconds(val.end_time))
      ) {
         this.$toasted.info("This Class is not yet available");
      }
      

      if (
        this.getSeconds(this.today) >= this.getSeconds(val.start_time) &&
        this.getSeconds(this.today) <= this.getSeconds(val.end_time)
      ) {
        axios
          .post(`/api/attendance`, data, {
            headers: {
              Authorization: `Bearer ${this.$props.student.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 201 || res.status == 200) {
              window.open(
                "https://bootstrap-vue.org/docs/components/input-group",
                "_blank"
              );
            }
          });
      }
    },
    getLive() {
      axios
        .get(`/api/student-live`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.todaysClass = res.data;
          }
        });
    },
    getSeconds(hms) {
      var a = hms.split(":"); // split it at the colons

      // minutes are worth 60 seconds. Hours are worth 60 minutes.
      var seconds = +a[0] * 60 * 60 + +a[1] * 60 + +a[2];
      return seconds;
    },
  },
};
</script>

<style scoped>
.container-fluid {
  margin-top: 30px;
  padding-left: 20px;
  padding-right: 20px;
}
p.card-text {
  font-size: 15px;
}
h4.card-title {
  font-size: 17px;
}
.ongoing {
  border-color: #008e3a;
}
.upcoming {
  border-color: #ffc200;
}
.finished {
  border-color: red;
}
.ongoing-i {
  color: #008e3a;
}
.upcoming-i {
  color: #ffc200;
}
.finished-i {
  color: red;
}
.ongoing-bg {
  /* background-color: #008e3a; */
  background-color: hsl(145deg 100% 85%);
}
.upcoming-bg {
  /* background-color: #ffc200; */
  background-color: hsl(46deg 100% 85%);
}
.finished-bg {
  /* background-color: red; */
  background-color: hsl(0deg 100% 85%);
}
</style>