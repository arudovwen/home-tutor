<template>
  <div class="views">
    <b-container>
      <b-row>
        <b-col md="8">
          <div class="main-view">
            <div v-if="type=='video'" class="w-100 video-player">
              <video id="video" :src="src" height="500" controls class="player"></video>

           
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
                <div class="navigate" v-if="pageCount > 0">
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
          <div class="side-view" >
            <h4 class="mb-3">Course Content</h4>
            <h5>{{module}}</h5>
            <p>{{excerpt}}</p>
             <div class="mod">
                        <ul>
                          <li v-for="(content,index) in content" :key="index">
                            <span
                              @click="play(content)"
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
          </div>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import pdf from "vue-pdf";
export default {
  props: ['module','excerpt',"content"],
  data() {
    return {
     src:[],
     title:'',
     overview:'',
     type:'',
      num: 0,
      src: "",
      page: 1,
      contentLength: 0,
      showPrev: false,
      showNext: true,
      currentPage: 0,
      pageCount: 0,
    };
  },
  components: {
    pdf,
  },

  created() {
   
  },
 mounted() {
       this.src =this.$props.content[0].file;
                  this.type = this.$props.content[0].type;
                  this.title =this.$props.content[0].title;
                  this.overview =this.$props.content[0].overview;
 },
  methods: {

  play(item) {
      this.src = item.file;
      this.type = item.type;
      this.title = item.title;

      this.overview = item.overview;
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
  display: flex;
  justify-content: center;
  align-items: center;
  height: 90%;
  position: relative;
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