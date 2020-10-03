<template>
  <div class="views pt-3">
    <b-container>
      <b-row>
        <b-col md="8">
          <div class="main-view">
            <div v-if="type=='video'" class="w-100 video-player">
              <video id="video" :src="src" height="500" controls class="player"></video>

              <!-- <div>
          <span id="played">0</span> seconds out of
          <span id="duration"></span> seconds. (only updates when the video pauses)
              </div>-->
            </div>
            <div v-else-if="type=='audio'" class="audio-player">
              <audio :src="src" height="300" controls class="player"></audio>
            </div>
            <div v-else-if="type=='pdf'">
              <div
                class="pdf_container mb-4"
                :key="num"
                :class="{'flex-column-reverse':!pageCount}"
              >
                <div class="texxt-center d-flex align-items-center loade">
                  <h5 class="josefin text-center" v-if="!pageCount">
                    Loading pdf ...
                    <div class="spinner-border text-main"></div>
                  </h5>
                </div>
                <div class="navigate pb-2" v-if="pageCount > 0">
                  <span class="d-flex align-items-center mr-2">
                    <i class="fa fa-minus-circle text-main" aria-hidden="true" @click="prevPage"></i>

                    <input type="number" class="pageNum text-center" v-model="page" />

                    <i class="fa fa-plus-circle text-main" aria-hidden="true" @click="nextPage"></i>
                  </span>
                  /
                  <span class="ml-2">{{pageCount}}</span>
                </div>
                <pdf
                  :src="src"
                  id="pdfBody"
                  style=" width: 100%;height:70vh;overflow:scroll;margin:0 auto"
                  class="pdfView"
                  :page="page"
                  @num-pages="pageCount = $event"
                ></pdf>
              </div>
            </div>
            <div v-else></div>

            <div class="buttons my-4">
              <a :href="src" download>
                <button type="button" class="btn btn-primary">Download</button>
              </a>
            </div>
            <hr />
            <div class="info">
              <h4>{{title}}</h4>
              <p>{{overview}}</p>
            </div>
          </div>
        </b-col>
        <b-col md="4">
          <div class="side-view pt-3" v-if="show">
            <h5 class="mb-3">Course Content</h5>

            <div role="tablist" v-for="(item,idx) in modules" :key="idx">
              <b-card no-body class="mb-1">
                <b-card-header header-tag="header" class="p-1 text-left" role="tab">
                  <b-button
                    block
                    v-b-toggle="item.module.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                    variant="secondary"
                    class="text-left toCaps"
                  >{{item.module}}</b-button>
                </b-card-header>
                <b-collapse
                  :id="item.module.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                  accordion="my-accordion"
                  role="tabpanel"
                >
                  <b-card-body>
                    <b-card-text>{{item.excerpt}}</b-card-text>
                    <b-card-text>
                      <div class="mod">
                        <ul>
                          <li v-for="(content,index) in JSON.parse(item.content)" :key="index" class="cpointer">
                            <span
                              @click="play(item.module,content.file,content.type,content.title,content.overview)"
                            >
                              <span class="ml-3">
                                <i
                                  class="fa fa-play-circle"
                                  v-if="content.type=='video'"
                                  aria-hidden="true"
                                ></i>
                                <i
                                  class="fa fa-file-pdf-o"
                                  v-if="content.type=='pdf'"
                                  aria-hidden="true"
                                ></i>
                                <i
                                  class="fa fa-volume-up"
                                  v-if="content.type=='audio'"
                                  aria-hidden="true"
                                ></i>
                                <i
                                  class="fa fa-file-powerpoint-o"
                                  v-if="content.type=='ppt'"
                                  aria-hidden="true"
                                ></i>
                                <i class="fas fa-file-csv" v-if="content.type=='csv'"></i>
                              </span>
                              {{content.title}}
                            </span>

                            <b-modal
                              :id="content.title.replace(/[^a-z0-9]/gi, '').replace(/\$/g, '')"
                              :title="content.title"
                              hide-footer
                            >
                              <p class="my-4">{{content.overview}}</p>
                            </b-modal>
                          </li>
                        </ul>
                      </div>
                    </b-card-text>
                  </b-card-body>
                </b-collapse>
              </b-card>
            </div>
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import pdf from "vue-pdf";
export default {
  props: ["student"],
  data() {
    return {
      modules: [],
      library: {},
      type: "video",
      fullScreen: false,
      num: 0,
      src: "",
      page: 1,
      contentLength: 0,
      showPrev: false,
      showNext: true,
      currentPage: 0,
      pageCount: 0,
      openAssessment: false,
      show: false,
      title: "",
      overview: "",
      lastDuration: 0,
      details: {},
    };
  },
  components: {
    pdf,
  },

  created() {
    this.getLibrary();
  },
  mounted() {
    this.checkPlayStat();
  },
  watch: {
    num: "changepdf",
  },
  beforeRouteLeave(to, from, next) {
    this.progress(this.details);
    next();
  },
  methods: {
    getLibrary() {
      axios
        .get(`/api/library/${this.$route.params.id}`, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.library = res.data;

            axios
              .get(
                `/api/get-module/${res.data.subject}/${this.$props.student.level}/${this.$props.student.school_id}`
              )
              .then((res) => {
                if (res.status == 200) {
                  this.modules = res.data;

                  this.src = JSON.parse(this.modules[0].content)[0].file;
                  this.type = JSON.parse(this.modules[0].content)[0].type;
                  this.title = JSON.parse(this.modules[0].content)[0].title;
                  this.overview = JSON.parse(
                    this.modules[0].content
                  )[0].overview;
                  let lastRead = JSON.parse(localStorage.getItem("lastRead"));
                  lastRead.title = JSON.parse(this.modules[0].content)[0].title;
                  lastRead.type = JSON.parse(this.modules[0].content)[0].type;
                  lastRead.index = 0;
                  localStorage.setItem("lastRead", JSON.stringify(lastRead));
                  this.show = true;
                }
              });
          }
        });
    },
    scrollUp() {
      //   window.scrollTo(0, 0);
      //   let body = document.getElementById("pdfBody");
      //   body.scrollTop = 0;
    },
    goFull() {
      this.fullScreen = !this.fullScreen;
    },
    nextPage() {
      if (this.page !== this.pageCount) {
        this.page++;
        this.scrollUp();
      }
    },
    prevPage() {
      if (this.page > 1) {
        this.page--;
        this.scrollUp();
      }
    },
    changepdf() {
      this.page = 1;
      this.scrollUp();
    },
    next() {
      if (this.num < this.contentLength) {
        this.num++;
        this.showNext = true;
      }
      if (this.num == this.contentLength - 1) {
        this.showNext = false;
      }

      if (this.num > 0) {
        this.showPrev = true;
      }
      if (this.num == 0) {
        this.showPrev = false;
      }
    },
    prev() {
      if (this.num > 0) {
        this.num--;
        this.showPrev = true;
      }
      if (this.num == 0) {
        this.showPrev = false;
      }
      if (this.num !== this.contentLength) {
        this.showNext = true;
      } else {
        this.showNext = false;
      }
    },
    progress(val) {
      axios
        .put(`/api/library/${this.$route.params.id}`, val, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        })
        .then((res) => {});
    },
    storeHistory(data){
        axios.post('/api/resource/history', data, {
          headers: {
            Authorization: `Bearer ${this.$props.student.access_token}`,
          },
        }).then(res=>{
          if (res.status == 201) {
            
          }
        })
    },
    checkPlayStat() {
      var video = document.getElementById("video");

      var timeStarted = -1;
      var timePlayed = 0;
      var duration = 0;
      // If video metadata is laoded get duration
      if (video.readyState > 0) getDuration.call(video);
      //If metadata not loaded, use event to get it
      else {
        video.addEventListener("loadedmetadata", getDuration);
      }
      // remember time user started the video
      function videoStartedPlaying() {
        timeStarted = new Date().getTime() / 1000;
      }
      function videoStoppedPlaying(event) {
        // Start time less then zero means stop event was fired vidout start event
        if (timeStarted > 0) {
          var playedFor = new Date().getTime() / 1000 - timeStarted;

          timeStarted = -1;
          // add the new ammount of seconds played
          timePlayed += playedFor;
          this.lastDuration = Math.round(timePlayed);
        }
        document.getElementById("played").innerHTML =
          Math.round(timePlayed) + "";
        // Count as complete only if end of video was reached
        if (timePlayed >= duration && event.type == "ended") {
          document.getElementById("status").className = "complete";
        }
      }

      function getDuration() {
        duration = video.duration;
        document
          .getElementById("duration")
          .appendChild(new Text(Math.round(duration) + ""));
      }

      video.addEventListener("play", videoStartedPlaying);
      video.addEventListener("playing", videoStartedPlaying);

      video.addEventListener("ended", videoStoppedPlaying);
      video.addEventListener("pause", videoStoppedPlaying);
    },
    play(module, file, type, title, overview) {
      var arr = [];
      this.modules.forEach((item) => {
        JSON.parse(item.content).forEach((i) => {
          arr.push(i.title);
        });
      });
      var ind = 0;
      ind = arr.indexOf(title);

      var total = arr.length;
      this.src = file;
      this.type = type;
      this.title = title;

      this.overview = overview;
      var lastRead = JSON.parse(localStorage.getItem("lastRead"));

      if (ind >= lastRead.index) {
        lastRead.title = title;
        lastRead.type = type;
        lastRead.module = module;
        lastRead.index = ind;
        var progress = ((ind + 1) / arr.length) * 100;
        lastRead.progress = Math.round(progress);
        if (type == "video") {
          lastRead.timePlayed = this.lastDuration;
        } else {
          lastRead.timePlayed = 0;
        }
      }
      this.details = lastRead;
      this.storeHistory(lastRead)
      localStorage.setItem("lastRead", JSON.stringify(lastRead));
      if (type == "pdf") {
        this.changepdf();
      }
    },
  },
};
</script>

<style scoped>
.views {
  display: flex;
  height: 100%;
}
/* .main-view {
  width: 70%;
  height: 100%;
}
.side-view {
  width: 30%;
  background: #f7f8f8;
  height: 100%;
  padding: 20px;
} */
.player {
  width: 100%;
  margin: 0 auto;
  background: black;
}
.audio-player {
  padding: 40px 15px;
}
.screen {
  width: 70%;
  min-height: 100vh;
  padding: 15px;
  position: relative;
}
.fullOpen {
  position: absolute;
  top: 0;
  left: 0;
  padding: 5px;
  background: #f7f8fa;
}
.pageNum {
  width: 30px;
  border: none !important;
}
.navigate {
  display: flex;
  align-items: center;
}
.content_view {
  width: 30%;
  min-height: 100vh;
  padding: 15px;
  background: #f7f8fa;
}
.pdf_container {
  width: 100%;
  /* display: flex;
  justify-content: center;
  align-items: center; */
  height: 100%;
  position: relative;
  padding-top:15px
}
.loade {
  position: absolute;
}
.mod ul {
  list-style: none;
}
.mod ul li {
  padding: 10px 20px;
  border-bottom: 1px solid #ccc;
}
.pdf_about {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  min-height: 250px;
  overflow: scroll;
  padding-bottom: 20px;
}
.pdf_title {
  text-transform: capitalize;
}
.cover_image {
  width: 100px;
  height: auto;
}
.controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.view {
  overflow-y: scroll;
  max-height: 60vh;
}
.info {
  padding: 0 15px;
}
.buttons {
  padding: 0 15px;
}
.link-title {
  cursor: pointer;
}
.view-inner {
  padding: 20px 10px;
}
@media screen and (max-width: 900px) {
  /* .main-view{
    width: 60%;
  }
  .side-view{
    width: 40%;
  }
  .btn-block{
    width: initial;
  }
  .collapse-card{

  } */
  .course-content {
    padding-bottom: 20px;
  }
}
</style>