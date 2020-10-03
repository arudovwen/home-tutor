<template>
  <div class="assessment">
    <b-tabs content-class v-model="tabIndex">
      <b-tab title="Assignment" active>
        <b-container fluid>
          <b-row>
            <b-col md="8" class="assessment-table">
              <div class="bd-table">
                <b-row class="mb-5 justify-content-between">
                  <b-col cols="3" sm="3" class="p-0 mb-3">
                    <b-form-select v-model="level" size="sm">
                      <b-form-select-option value="">Select</b-form-select-option>
                      <b-form-select-option
                        v-for="(level, idx) in JSON.parse(tutor.levels)"
                        :key="idx"
                        :value="level"
                        >{{ level }}</b-form-select-option
                      >
                    </b-form-select>
                  </b-col>
                  <b-col class="text-right mb-3">
                    <router-link to="/tutor/assignment" class="ml-auto">
                      <b-button size="sm" variant="lightblue"
                        >All Assignments</b-button
                      >
                    </router-link>
                  </b-col>
                </b-row>
                <b-table
                  responsive
                  head-variant="lightblue"
                  bordered
                  :fields="fields"
                  :items="ass"
                  v-if="ass.length"
                >
                  <template v-slot:cell(Sn)="data">{{
                    data.index + 1
                  }}</template>
                  <template v-slot:cell(publish_status)="data">
                    <span v-if="data.item.publish_status == true"
                      >Published</span
                    >
                    <span v-else>Unpublished</span>
                  </template>
                  <template v-slot:cell(title)="data">
                    <div class="main-title" v-if="data.item.option">
                      <div
                        class="title"
                        @click="
                          getNotification(JSON.parse(data.item.option).title)
                        "
                      >
                        {{ JSON.parse(data.item.option).title }}
                      </div>
                      <div class="text-muted">
                        {{ JSON.parse(data.item.option).title }}
                      </div>
                    </div>
                  </template>
                  <template v-slot:cell(action)="data">
                    <span
                      @click="publish(true, data.item.id)"
                      v-if="data.item.publish_status == false"
                      >Publish</span
                    >
                    <span @click="publish(false, data.item.id)" v-else
                      >Unpublish</span
                    >
                  </template>
                </b-table>
                <b-alert v-else show>No Data</b-alert>
              </div>
            </b-col>
            <b-col md="4">
              <div class="right">
                <h6 class="notify">Assignment Notification</h6>
                <p class="toCaps" v-if="title">Title : {{ title }}</p>
                <div class="overall-assessment">
                  <div
                    class="submitted"
                    @click="handleSubmitted(level, assResult)"
                    id="submitted-1"
                  >
                    <i class="fa fa-check"></i>
                    <h6>{{ handleAssess(level, assResult).length }}</h6>
                    <p>submitted</p>
                    <!-- <b-popover ref="popover"  target="submitted-1" placement="bottom">
                      <ul class="pop">
                        <li
                          v-for="(sub,idx) in submitted"
                          :key="idx"
                          class="toCaps"
                        >{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="graded"
                    @click="handleGrade(level, assResult)"
                    id="graded-1"
                  >
                    <i class="fa fa-check-double"></i>
                    <h6>{{ handleGraded(level, assResult).length }}</h6>
                    <p>Graded</p>
                    <!-- <b-popover ref="popover" target="graded-1" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in graded" :key="idx" class="toCaps">{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="pending"
                    @click="handlePending(level, assResult)"
                    id="pending-1"
                  >
                    <i class="fa fa-ban"></i>
                    <h6>{{ handlePend(level, assResult).length }}</h6>
                    <p>pending</p>
                    <!-- <b-popover ref="popover" target="pending-1" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in pending" :key="idx" class="toCaps">{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                </div>

                <div>
                  <b-form-input
                    placeholder="Search title"
                    v-model="search"
                  ></b-form-input>
                </div>
                <div class="assignment-notification">
                  <div class="notification-content">
                    <div
                      v-for="(item, idx) in assResult.filter(
                        (item) => item.status == showSubmitted
                      )"
                      :key="idx"
                      @click="getContent(item)"
                    >
                      <p>
                        {{ item.user.name }} submitted {{ item.subject }}
                        {{ item.type }}
                      </p>
                      <small
                        class="toCaps"
                        :class="{
                          approved: item.status == 'submitted',
                          pendings: item.status == 'pending',
                        }"
                      >
                        <span>{{
                          item.status == "submitted" ? "Graded" : "Pending"
                        }}</span>
                        <b-icon
                          :icon="
                            item.status == 'submitted'
                              ? 'check-circle-fill'
                              : 'clock-fill'
                          "
                        ></b-icon>
                      </small>
                      <div class="notify-class">
                        <p>{{ item.level }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </b-col>
          </b-row>
        </b-container>
      </b-tab>
      <b-tab title="Quiz">
        <b-container fluid>
          <b-row>
            <b-col md="8" class="assessment-table">
              <div class="bd-table">
                <b-row class="mb-5 justify-content-between">
                  <b-col cols="3" sm="3" class="p-0 mb-3">
                    <b-form-select v-model="level" size="sm">
                        <b-form-select-option value="">Select</b-form-select-option>
                      <b-form-select-option
                        v-for="(level, idx) in JSON.parse(tutor.levels)"
                        :key="idx"
                        :value="level"
                        >{{ level }}</b-form-select-option
                      >
                    </b-form-select>
                  </b-col>
                  <b-col class="text-right mb-3">
                    <router-link to="/tutor/quiz" class="ml-auto">
                      <b-button size="sm" variant="lightblue"
                        >All Quizzes</b-button
                      >
                    </router-link>
                  </b-col>
                </b-row>
                <b-table
                  responsive
                  head-variant="lightblue"
                  bordered
                  :fields="fields"
                  :items="qu"
                  hover
                  v-if="qu.length"
                >
                  <template v-slot:cell(Sn)="data">{{
                    data.index + 1
                  }}</template>
                  <template v-slot:cell(publish_status)="data">
                    <span v-if="data.item.publish_status == true"
                      >Published</span
                    >
                    <span v-else>Unpublished</span>
                  </template>

                  <template v-slot:cell(title)="data">
                    <div class="main-title" v-if="data.item.option">
                      <div
                        class="title"
                        @click="
                          getNotification(JSON.parse(data.item.option).title)
                        "
                      >
                        {{ JSON.parse(data.item.option).title }}
                      </div>
                      <div class="text-muted">
                        {{ JSON.parse(data.item.option).description }}
                      </div>
                    </div>
                  </template>
                  <template v-slot:cell(action)="data">
                    <span
                      @click="publish(true, data.item.id)"
                      v-if="data.item.publish_status == false"
                      >Publish</span
                    >
                    <span @click="publish(false, data.item.id)" v-else
                      >Unpublish</span
                    >
                  </template>
                </b-table>
                 <b-alert v-else show>No Data</b-alert>
              </div>
            </b-col>
            <b-col md="4" class="notify-board">
              <div class="right">
                <h6 class="notify">Quiz Notification</h6>
                <p class="toCaps" v-if="title">Title : {{ title }}</p>
                <div class="overall-assessment">
                  <div
                    class="submitted"
                    @click="handleSubmitted(level, quizResult)"
                    id="submitted-2"
                  >
                    <i class="fa fa-check"></i>
                    <h6>{{ handleAssess(level, quizResult).length }}</h6>
                    <p>submitted</p>
                    <!-- <b-popover ref="popover" target="submitted-2" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in submitted" :key="idx" class="toCaps">{{sub}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="graded"
                    @click="handleGrade(level, quizResult)"
                    id="graded-2"
                  >
                    <i class="fa fa-check-double"></i>
                    <h6>{{ handleGraded(level, quizResult).length }}</h6>
                    <p>Graded</p>
                    <!-- <b-popover ref="popover" target="graded-2" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in graded" :key="idx" class="toCaps">{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="pending"
                    @click="handlePending(level, quizResult)"
                    id="pending-2"
                  >
                    <i class="fa fa-ban"></i>
                    <h6>{{ handlePend(level, quizResult).length }}</h6>

                    <p>pending</p>
                    <!-- <b-popover ref="popover" target="pending-2" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in pending" :key="idx" class="toCaps">{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                </div>

                <div>
                  <b-form-input
                    placeholder="Search title"
                    v-model="search"
                  ></b-form-input>
                </div>
                <div class="assignment-notification">
                  <div class="notification-content">
                    <div
                      v-for="(item, idx) in quizResult.filter(
                        (item) => item.status == showSubmitted
                      )"
                      :key="idx"
                      @click="getContent(item)"
                    >
                      <p>
                        {{ item.user.name }} submitted {{ item.subject }}
                        {{ item.type }}
                      </p>
                      <small
                        class="toCaps"
                        :class="{
                          approved: item.status == 'submitted',
                          pendings: item.status == 'pending',
                        }"
                      >
                        <span>{{
                          item.status == "submitted" ? "Graded" : "Pending"
                        }}</span>
                        <b-icon
                          :icon="
                            item.status == 'submitted'
                              ? 'check-circle-fill'
                              : 'clock-fill'
                          "
                        ></b-icon>
                      </small>
                      <div class="notify-class">
                        <p>{{ item.level }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </b-col>
          </b-row>
        </b-container>
      </b-tab>
      <b-tab title="Test">
        <b-container fluid>
          <b-row>
            <b-col md="8" class="assessment-table">
              <div class="bd-table">
                <b-row class="mb-5 justify-content-between">
                <b-col cols="3" sm="3" class="p-0 mb-3">
                    <b-form-select v-model="level" size="sm">
                        <b-form-select-option value="">Select</b-form-select-option>
                      <b-form-select-option
                        v-for="(level, idx) in JSON.parse(tutor.levels)"
                        :key="idx"
                        :value="level"
                        >{{ level }}</b-form-select-option
                      >
                    </b-form-select>
                  </b-col>
                  <b-col class="text-right mb-3">
                    <router-link to="/tutor/test" class="ml-auto">
                      <b-button size="sm" variant="lightblue"
                        >All Tests</b-button
                      >
                    </router-link>
                  </b-col>
                </b-row>
                <b-table
                  responsive
                  head-variant="lightblue"
                  bordered
                  :fields="fields"
                  :items="tes"
                  hover
                  v-if="tes.length"
                >
                  <template v-slot:cell(Sn)="data">{{
                    data.index + 1
                  }}</template>
                  <template v-slot:cell(action)="data">
                    <span
                      @click="publish(true, data.item.id)"
                      v-if="data.item.publish_status == false"
                      >Publish</span
                    >
                    <span @click="publish(false, data.item.id)" v-else
                      >Unpublish</span
                    >
                  </template>
                  <template v-slot:cell(title)="data">
                    <div class="main-title" v-if="data.item.option">
                      <div
                        class="title"
                        @click="
                          getNotification(JSON.parse(data.item.option).title)
                        "
                      >
                        {{ JSON.parse(data.item.option).title }}
                      </div>
                      <div class="text-muted">{{ data.item.description }}</div>
                    </div>
                  </template>
                  <template v-slot:cell(publish_status)="data">
                    <span v-if="data.item.publish_status == true"
                      >Published</span
                    >
                    <span v-else>Unpublished</span>
                  </template>
                </b-table>
                 <b-alert v-else show>No Data</b-alert>
              </div>
            </b-col>
            <b-col md="4">
              <div class="right">
                <h6 class="notify">Test Notification</h6>
                <p class="toCaps" v-if="title">Title : {{ title }}</p>
                <div class="overall-assessment">
                  <div
                    class="submitted"
                    @click="handleSubmitted(level, testResult)"
                    id="submitted-3"
                  >
                    <i class="fa fa-check"></i>
                    <h6>{{ handleAssess(level, testResult).length }}</h6>
                    <p>Submitted</p>
                    <!-- <b-popover ref="popover" target="submitted-3" placement="bottom">
                      <ul class="pop">
                        <li
                          v-for="(sub,idx) in submitted"
                          :key="idx"
                          class="toCaps"
                    >{{sub.user.name}}</li>-->
                    <!-- </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="graded"
                    @click="handleGrade(level, testResult)"
                    id="graded-3"
                  >
                    <i class="fa fa-check-double"></i>
                    <h6>{{ handleGraded(level, testResult).length }}</h6>
                    <p>Graded</p>
                    <!-- <b-popover ref="popover" target="graded-3" placement="bottom">
                      <ul class="pop">
                        <li
                          v-for="(sub,idx) in submitted"
                          :key="idx"
                          class="toCaps"
                        >{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="pending"
                    @click="handlePending(level, testResult)"
                    id="pending-3"
                  >
                    <i class="fa fa-ban"></i>
                    <h6>{{ handlePend(level, testResult).length }}</h6>
                    <p>Pending</p>
                    <!-- <b-popover ref="popover" target="pending-3" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in pending" :key="idx" class="toCaps">{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                </div>

                <div>
                  <b-form-input
                    placeholder="Search title"
                    v-model="search"
                  ></b-form-input>
                </div>
                <div class="assignment-notification">
                  <div class="notification-content">
                    <div
                      v-for="(item, idx) in testResult.filter(
                        (item) => item.status == showSubmitted
                      )"
                      :key="idx"
                      @click="getContent(item)"
                    >
                      <p>
                        {{ item.user.name }} submitted {{ item.subject }}
                        {{ item.type }}
                      </p>
                      <small
                        class="toCaps"
                        :class="{
                          approved: item.status == 'submitted',
                          pendings: item.status == 'pending',
                        }"
                      >
                        <span>{{
                          item.status == "submitted" ? "Graded" : "Pending"
                        }}</span>
                        <b-icon
                          :icon="
                            item.status == 'submitted'
                              ? 'check-circle-fill'
                              : 'clock-fill'
                          "
                        ></b-icon>
                      </small>
                      <div class="notify-class">
                        <p>{{ item.level }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </b-col>
          </b-row>
        </b-container>
      </b-tab>
      <b-tab title="Examination">
        <b-container fluid>
          <b-row>
            <b-col md="8" class="assessment-table">
              <div class="bd-table">
                <b-row class="mb-5 justify-content-between">
                 <b-col cols="3" sm="3" class="p-0 mb-3">
                    <b-form-select v-model="level" size="sm">
                        <b-form-select-option value="">Select</b-form-select-option>
                      <b-form-select-option
                        v-for="(level, idx) in JSON.parse(tutor.levels)"
                        :key="idx"
                        :value="level"
                        >{{ level }}</b-form-select-option
                      >
                    </b-form-select>
                  </b-col>
                  <b-col class="text-right mb-3">
                    <router-link to="/tutor/exam" class="ml-auto">
                      <b-button size="sm" variant="lightblue"
                        >All Exams</b-button
                      >
                    </router-link>
                  </b-col>
                </b-row>
                <b-table
                  responsive
                  head-variant="lightblue"
                  bordered
                  :fields="fields"
                  :items="ex"
                  hover
                  v-if="ex.length"
                >
                  <template v-slot:cell(Sn)="data">{{
                    data.index + 1
                  }}</template>
                  <template v-slot:cell(action)="data">
                    <span
                      @click="publish(true, data.item.id)"
                      v-if="data.item.publish_status == false"
                      >Publish</span
                    >
                    <span @click="publish(false, data.item.id)" v-else
                      >Unpublish</span
                    >
                  </template>

                  <template v-slot:cell(title)="data">
                    <div class="main-title" v-if="data.item.option">
                      <div
                        class="title"
                        @click="
                          getNotification(JSON.parse(data.item.option).title)
                        "
                      >
                        {{ JSON.parse(data.item.option).title }}
                      </div>
                      <div class="text-muted">
                        {{ JSON.parse(data.item.option).description }}
                      </div>
                    </div>
                  </template>
                  <template v-slot:cell(publish_status)="data">
                    <span v-if="data.item.publish_status == true"
                      >Published</span
                    >
                    <span v-else>Unpublished</span>
                  </template>
                </b-table>
                 <b-alert v-else show>No Data</b-alert>
              </div>
            </b-col>
            <b-col md="4">
              <div class="right">
                <h6 class="notify mb-2">Exam Notification</h6>
                <p class="toCaps" v-if="title">Title : {{ title }}</p>
                <div class="overall-assessment">
                  <div
                    class="submitted"
                    @click="handleSubmitted(level, examResult)"
                    id="submitted-4"
                  >
                    <i class="fa fa-check"></i>
                    <h6>{{ handleAssess(level, examResult).length }}</h6>
                    <p>Submitted</p>
                    <!-- <b-popover ref="popover" target="submitted-4" placement="bottom">
                      <ul class="pop">
                        <li
                          v-for="(sub,idx) in submitted"
                          :key="idx"
                          class="toCaps"
                        >{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="graded"
                    @click="handleGrade(level, examResult)"
                    id="graded-4"
                  >
                    <i class="fa fa-check-double"></i>
                    <h6>{{ handleGraded(level, examResult).length }}</h6>
                    <p>Graded</p>
                    <!-- <b-popover ref="popover" target="graded-4" placement="bottom">
                      <ul class="pop">
                        <li
                          v-for="(sub,idx) in submitted"
                          :key="idx"
                          class="toCaps"
                        >{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                  <div
                    class="pending"
                    @click="handlePending(level, examResult)"
                    id="pending-4"
                  >
                    <i class="fa fa-ban"></i>
                    <h6>{{ handlePend(level, examResult).length }}</h6>
                    <p>Pending</p>
                    <!-- <b-popover ref="popover" target="pending-4" placement="bottom">
                      <ul class="pop">
                        <li v-for="(sub,idx) in pending" :key="idx" class="toCaps">{{sub.user.name}}</li>
                      </ul>
                    </b-popover>-->
                  </div>
                </div>

                <div>
                  <b-form-input
                    placeholder="Search title"
                    v-model="search"
                  ></b-form-input>
                </div>
                <div class="assignment-notification">
                  <div class="notification-content">
                    <div
                      v-for="(item, idx) in examResult.filter(
                        (item) => item.status == showSubmitted
                      )"
                      :key="idx"
                      @click="getContent(item)"
                    >
                      <p>
                        {{ item.user.name }} submitted {{ item.subject }}
                        {{ item.type }}
                      </p>
                      <small
                        class="toCaps"
                        :class="{
                          approved: item.status == 'submitted',
                          pendings: item.status == 'pending',
                        }"
                      >
                        <span>{{
                          item.status == "submitted" ? "Graded" : "Pending"
                        }}</span>
                        <b-icon
                          :icon="
                            item.status == 'submitted'
                              ? 'check-circle-fill'
                              : 'clock-fill'
                          "
                        ></b-icon>
                      </small>
                      <div class="notify-class">
                        <p>{{ item.level }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </b-col>
          </b-row>
        </b-container>
      </b-tab>
      <b-tab title="All Assessments" v-if="tutor.head">
        <b-container>
          <b-row class="my-3">
            <b-col cols="3">
              <b-form-group label="Subject">
                <b-form-select v-model="subject">
                  <b-form-select-option value>Select</b-form-select-option>
                  <b-form-select-option
                    :value="item.name"
                    v-for="(item, idx) in subjects"
                    :key="idx"
                    >{{ item.name }}</b-form-select-option
                  >
                </b-form-select>
              </b-form-group>
            </b-col>

            <b-col cols="2">
              <b-form-group label="Format">
                <b-form-select v-model="type">
                  <b-form-select-option value="select"
                    >Select</b-form-select-option
                  >
                  <b-form-select-option
                    :value="item"
                    v-for="(item, idx) in formats"
                    :key="idx"
                    >{{ item }}</b-form-select-option
                  >
                </b-form-select>
              </b-form-group>
            </b-col>
            <b-col cols="4">
              <b-form-group label="Search ">
                <b-form-input
                  placeholder="Search"
                  v-model="search"
                ></b-form-input>
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-table :fields="fieldss" :items="sorted" responsive v-if="sorted.length">
                <template v-slot:cell(action)="data">
                  <div class="options">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    <div class="option shadow">
                      <ul>
                        <li>
                          <span @click="verify(data.item.id, 'approved')"
                            >Approve</span
                          >
                          <span @click="verify(data.item.id, 'declined')"
                            >Decline</span
                          >
                        </li>
                        <li @click="viewall(data.item)">View</li>
                        <!-- <li @click="drop(data.item.id)">Drop</li> -->
                      </ul>
                    </div>
                  </div>
                </template>
                <template v-slot:cell(created_at)="data">{{
                  data.item.created_at | moment("MMM D YYYY")
                }}</template>
                <template v-slot:cell(Sn)="data">{{ data.index + 1 }}</template>
              </b-table>
               <b-alert v-else show>No Data</b-alert>
            </b-col>
          </b-row>
        </b-container>
      </b-tab>
    </b-tabs>
    <b-modal id="content" size="xl" title="Review" hide-footer>
      <Review
        @refresh="refresh"
        :id="id"
        :overall="overall"
        :name="name"
        :form="form"
        :options="options"
      />
    </b-modal>
    <b-modal id="contentS" size="xl" title="Review" hide-footer>
      <ReviewS
        @refresh="refresh"
        :id="id"
        :overall="overall"
        :name="name"
        :form="form"
        :options="options"
      />
    </b-modal>
    <b-modal id="prev" title="Preview" size="xl" hide-footer>
      <Preview :options="optionss" :form="formie" />
    </b-modal>
  </div>
</template>


<script>
import Review from "./review";
import ReviewS from "./reviewS";
import Preview from "./preview";
export default {
  props: ["tutor"],
  data() {
    return {
      optionss: {
        title: "",
        subject: "",
        level: "",
        type: "",
        myclass: "",
        duration: {
          start_time: "",
          end_time: "",
        },
        session: "",
      },
      formie: [],
      subjects: [],
      all_classess: [],
      level: "",
      id: null,
      name: {},
      form: [],
      overall: null,
      options: {
        title: "",
        subject: "",
        level: "",
        type: "",
      },
      search: "",
      tabIndex: 0,
      tutors: [],
      busy: true,
      items: [],
      item: false,
      quiz: [],
      test: [],
      assignment: [],
      examination: [],
      draftResult: [],
      allClass: [],
      title: "",
      submitted: [],
      pending: [],
      graded: [],
      subject: "",
      type: "select",
      showSubmitted: "submitted",
      fields: [
        {
          key: "title",
          sortable: true,
        },
        "level",

        "status",
        "publish_status",
        "action",
      ],
      allStudents: [],
      formats: ["Assignment", "Exam", "Test", "Quiz"],
      id: null,
      fieldss: [
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
      allitemss: [],
    };
  },
  watch: {
    item: "selectAll",
    tabIndex: "handleSwitch",
  },
  components: {
    Review,
    ReviewS,
    Preview,
  },
  mounted() {
    this.getData();
    this.getDatas();
    this.getClasses();
 
    this.getSubjects();
  },
  computed: {
    sorted() {
      var search = this.allitemss.filter((item) =>
        item.title.toLowerCase().includes(this.search.toLowerCase())
      );

      var format = search.filter((item) =>
        item.type.toLowerCase().includes(this.type.toLowerCase())
      );

      var subject = format.filter((item) =>
        item.subject.toLowerCase().includes(this.subject.toLowerCase())
      );

      var level = subject.filter((item) =>
        item.level
          .toLowerCase()
          .includes(this.$props.tutor.head.class_name.toLowerCase())
      );

      return level;
    },
    draft() {
      var d = this.draftResult.filter((item) => {
        return item.level.toLowerCase().includes(this.level.toLowerCase());
      });
      return d.filter((item) => item.title.includes(this.title));
    },
    examResult() {
      return this.draft.filter((item) => {
        if (
          item.title.toLowerCase().includes(this.search.toLowerCase()) ||
          item.user.name.toLowerCase().includes(this.search.toLowerCase())
        ) {
          return item.type == "examination";
        }
      });
    },
    testResult() {
      return this.draft.filter((item) => {
        if (
          item.title.toLowerCase().includes(this.search.toLowerCase()) ||
          item.user.name.toLowerCase().includes(this.search.toLowerCase())
        ) {
          return item.type == "test";
        }
      });
    },
    assResult() {
      return this.draft.filter((item) => {
        if (
          item.title.toLowerCase().includes(this.search.toLowerCase()) ||
          item.user.name.toLowerCase().includes(this.search.toLowerCase())
        ) {
          return item.type == "assignment";
        }
      });
    },
    quizResult() {
      return this.draft.filter((item) => {
        if (
          item.title.toLowerCase().includes(this.search.toLowerCase()) ||
          item.user.name.toLowerCase().includes(this.search.toLowerCase())
        ) {
          return item.type == "quiz";
        }
      });
    },
    ass() {
      return this.assignment.filter((item) => {
        return item.level.toLowerCase().includes(this.level.toLowerCase());
      });
    },

    tes() {
      return this.test.filter((item) => {
        return item.level.toLowerCase().includes(this.level.toLowerCase());
      });
    },
    qu() {
      return this.quiz.filter((item) => {
        return item.level.toLowerCase().includes(this.level.toLowerCase());
      });
    },
    ex() {
      return this.examination.filter((item) => {
        return item.level.toLowerCase().includes(this.level.toLowerCase());
      });
    },
  },
  methods: {
    viewall(content) {
      this.overall = content.overall;

      this.optionss.title = content.title;
      this.optionss.subject = content.subject;
      this.optionss.myclass = content.level;
      this.optionss.session = content.session;
      this.optionss.type = content.type;
      this.optionss.duration.start_time = content.start;
      this.optionss.duration.end_time = content.end;
      this.formie = JSON.parse(content.assessment);

      this.$bvModal.show("prev");
    },
    getDatas() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/tutor-assessments`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allitemss = res.data;
          }
        })
        .catch();
    },
    getNotification(title) {
      this.title = title;
    },
    handleSwitch() {
      if (this.tabIndex == 0) {
        this.title = this.ass[0].title;
      }
      if (this.tabIndex == 1) {
        this.title = this.qu[0].title;
      }
      if (this.tabIndex == 2) {
        this.title = this.tes[0].title;
      }
      if (this.tabIndex == 3) {
        this.title = this.ex[0].title;
      }
    },
    handleAssess(level, arr) {
      return arr.filter(
        (item) => item.level.toLowerCase() == level.toLowerCase()
      );
    },
    handleGraded(level, arr) {
      return arr.filter(
        (item) =>
          item.level.toLowerCase() == level.toLowerCase() &&
          item.status == "submitted"
      );
    },
    handlePend(level, arr) {
      return arr.filter(
        (item) =>
          item.level.toLowerCase() == level.toLowerCase() &&
          item.status == "pending"
      );
    },
    handleStudent(level, arr) {
      return this.allStudents.filter(
        (item) => item.student_level.toLowerCase() == level.toLowerCase()
      );
    },
    handleSubmitted(level, arr) {
      this.submitted = arr.filter(
        (item) => item.level.toLowerCase() == level.toLowerCase()
      );
    },
    handleGrade(level, arr) {
      this.graded = arr.filter(
        (item) =>
          item.level.toLowerCase() == level.toLowerCase() &&
          item.status == "submitted"
      );
      this.showSubmitted = "submitted";
    },
    handlePending(level, arr) {
      this.pending = arr.filter(
        (item) =>
          item.level.toLowerCase() == level.toLowerCase() &&
          item.status == "pending"
      );
      this.showSubmitted = "pending";
    },
    getStudent(level) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get(`/api/students/${level}`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.allStudents = res.data;
          }
        });
    },
    getSubjects() {
      axios
        .get(`/api/tutor-all-subjects`, {
          headers: {
            Authorization: `Bearer ${this.$props.tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.subjects = res.data;
          }
        });
    },
    getClasses() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      axios
        .get("/api/all-classes", {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.all_classess = res.data;
           
          
          }
        });
    },
    refresh() {
      this.getDraftResult();
      this.getData();
      this.getDatas();
    },
    getContent(content) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      this.id = content.id;
      this.overall = content.overall;
      this.name = content.user;
      this.options.title = content.title;
      this.options.subject = content.subject;
      this.options.level = content.level;
      this.options.type = content.type;
      this.form = JSON.parse(content.record);

      if (content.status == "pending") {
        this.$bvModal.show("content");
      }
      if (content.status == "submitted") {
        axios
          .get(
            `/api/get-ass-result/${content.user_id}/${content.type}/${content.title}/${content.level}/${content.subject}`,
            {
              headers: {
                Authorization: `Bearer ${tutor.access_token}`,
              },
            }
          )
          .then((res) => {
           
            if (res.status == 200) {
              this.id = res.data.id;
              this.overall = res.data.overall;
              this.name = res.data.user;
              this.options.title = res.data.title;
              this.options.subject = res.data.subject;
              this.options.level = res.data.level;
              this.options.type = res.data.type;
              this.form = JSON.parse(res.data.record);

              this.$bvModal.show("contentS");
            }
          });
      }
    },
    publish(name, id) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let data = {
        stat: name,
      };
      axios
        .put(`/api/publish-assessment/${id}`, data, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.getData();
          }
        });
    },
    getDraftResult(title, subject, type) {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      let data = {
        title: title,
        subject: subject,
        type: type,
      };
      axios
        .post(`/api/draft-result`, data, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.draftResult = res.data;
            this.subject = subject;
            this.title = title;
            this.type = type;
          }
        });
    },
    getData() {
      let tutor = JSON.parse(localStorage.getItem("typeTutor"));
      this.quiz = [];
      this.test = [];
      this.assignment = [];
      this.examination = [];

      axios
        .get(`/api/assessment`, {
          headers: {
            Authorization: `Bearer ${tutor.access_token}`,
          },
        })
        .then((res) => {
          if (res.status == 200) {
            this.items = res.data;
            this.items.forEach((item) => {
              if (item.type == "quiz") {
                this.quiz.push(item);
              }
              if (item.type == "test") {
                this.test.push(item);
              }
              if (item.type == "assignment") {
                this.assignment.push(item);
              }
              if (item.type == "exam") {
                this.examination.push(item);
              }
            });
            if (this.assignment.length) {
              this.getDraftResult(
                this.assignment[0].title,
                this.assignment[0].subject,
                this.assignment[0].type
              );
            }
          }
        })
        .catch();
    },
    selectAll() {
      if (this.item) {
        this.items = [];
        this.tutors.forEach((it) => {
          this.items.push(it.id);
        });
      } else {
        this.items = [];
      }
    },
    verify(id, name) {
      let del = confirm("Are you sure?");
      if (del) {
        let data = {
          status: name,
        };
        axios
          .put(`/api/tutor-verify-assessment/${id}`, data, {
            headers: {
              Authorization: `Bearer ${this.$props.tutor.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getDatas();
            }
          });
      }
    },
    drop(id) {
      let del = confirm("Are you sure?");
      if (del) {
        axios
          .delete(`/api/tutor/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getAdmins();
            }
          });
      }
    },
    multiDrop() {
      let del = confirm("Are you sure about this?");
      let data = {
        data: this.items,
      };
      if (del) {
        axios
          .post("/api/multi-tutor-drop", data, {
            headers: {
              Authorization: `Bearer ${this.$props.admin.access_token}`,
            },
          })
          .then((res) => {
            if (res.status == 200) {
              this.getAdmins();
            }
          })
          .catch((err) => {
            console.log("del -> err", err.data);
          });
      }
    },
  },
};
</script>

<style scoped>
.container-fluid {
  width: 100% !important;
}
.main-title {
  width: 350px;
}
.title {
  font-weight: bold;
  color: #56cee3;
}
.row {
  margin-left: 0px !important;
}
.right {
  background: #fff;
  height: 100vh;
  padding: 10px;
  margin-top: 1rem;
}
.right-btn {
  display: flex;
  justify-content: flex-end;
  padding-top: 2rem;
}
/* .btn {
  background: transparent;
  text-decoration: underline;
  color: #0a4065;
  font-weight: 500;
}
.btn:hover {
  color: #118fe2;
} */
.approved {
  padding: 5px;
  background: #0a4065;
  color: white;
}
.pendings {
  padding: 5px;
  background: #ffc200;
  color: white;
}
.right h6 {
  padding-top: 1.5rem;
  font-size: 14px;
}
.assignment-notification {
  background: rgba(34, 202, 222, 0.2);
  border-left: 5px solid #22cade;
  padding: 5px 10px;
  margin-bottom: 1rem;
  margin-top: 1rem;
}
.notification-content p {
  font-size: 12px;
  margin-bottom: 0;
}
.notify-class {
  display: flex;
  justify-content: flex-end;
  color: #0a4065;
  font-weight: 500;
}
.notify {
  text-align: center;
  font-size: 17px;
  margin-bottom: 16px;
}
.assessment-table {
  padding-left: 15px;
  padding-top: 0;
}
.hiden {
  opacity: 0;
}
.overall-assessment {
  display: flex;
  justify-content: space-between;
  padding: 2px 10px;
}
.overall-assessment h6 {
  margin-bottom: 3px;
  padding-top: 5px;
}
.overall-assessment p {
  color: grey;
  font-size: 14px;
}
.submitted {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
a {
  text-decoration: none;
}
.graded {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.graded .fa-check-double {
  color: #008e3a;
  background: rgba(0, 142, 58, 0.25);
  border-radius: 50%;
  padding: 10px;
}
.pending {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.submitted .fa-check {
  color: #008e3a;
  background: #ccc;
  border-radius: 50%;
  padding: 10px;
}
.pending .fa-ban {
  color: #dfd291;
  background: rgba(223, 210, 145, 0.25);
  border-radius: 50%;
  padding: 10px;
}
ul,
ol {
  list-style: none;
}
.pop li {
  padding: 5px 10px;
}
@media (max-width: 600px) {
  .main-title {
    width: 250px;
  }
  .row{
    margin:0 !important;
  }
}
</style>