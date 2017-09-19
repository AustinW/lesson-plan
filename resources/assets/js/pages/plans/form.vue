<template>
  <card :title="$t('your_lesson_plan')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('plan_updated')"></alert-success>

      <!-- Title -->
      <div class="form-row">
        <div class="form-group col-md-12">
          <label class="col-form-label">{{ $t('title') }}</label>
          <input v-model="form.title" type="text" name="title" class="form-control"
                 :class="{ 'is-invalid': form.errors.has('title') }">
          <has-error :form="form" field="title"></has-error>
        </div>
      </div>


      <!-- Description -->
      <div class="form-row">
        <div class="form-group col-md-12">
          <label class="col-form-label">{{ $t('description') }}</label>
          <textarea v-model="form.description" name="description" class="form-control"
                    :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
          <has-error :form="form" field="description"></has-error>
        </div>
      </div>

      <div class="form-row">
        <!-- Date -->
        <div class="form-group col-md-6">
          <label class="col-form-label"><i class="fa fa-calendar"></i> {{ $t('date') }}</label>
          <datepicker v-model="form.date" name="date" input-class="form-control" format="D, MMM d, yyyy"
                      :class="{ 'is-invalid': form.errors.has('date') }"></datepicker>
          <has-error :form="form" field="date"></has-error>
        </div>

        <!-- Group -->
        <div class="form-group col-md-6">
          <label class="col-form-label"><i class="fa fa-group"></i> {{ $t('group') }}</label>
          <input v-model="form.group" type="text" name="group" class="form-control"
                 :class="{ 'is-invalid': form.errors.has('group') }">
          <has-error :form="form" field="group"></has-error>
        </div>
      </div>

      <hr>

      <!-- Sections -->
      <div class="form-row" v-for="section in form.sections">
        <div class="form-group col-md-4">
          <label class="col-form-label">{{ $t('event') }}</label>
          <input v-model="section.event" type="text" name="event" class="form-control">
          <label class="col-form-label">{{ $t('title') }}</label>
          <input v-model="section.title" type="text" name="title" class="form-control">
        </div>
        <div class="form-group col-md-8">
          <label class="col-form-label">Content</label>
          <textarea v-model="section.content" class="form-control" rows="5"></textarea>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">

        </div>

      </div>


      <!-- Submit Button -->
      <div class="form-row">
        <div class="form-group col-md-12 ml-md-auto">
          <a v-on:click="addSection" class="btn btn-outline-success"><i class="fa fa-plus"></i> Add Section</a>
          <v-button type="primary" :loading="form.busy">{{ $t('update') }}</v-button>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
  import Form from 'vform'
  import { mapGetters } from 'vuex'
  import axios from 'axios'
  import Datepicker from 'vuejs-datepicker'

  export default {
    components: {
      Datepicker
    },

    data: () => ({
      form: new Form({
        id: '',
        title: '',
        description: '',
        date: '',
        group: '',
        sections: []
      }),
    }),

    computed: mapGetters({
      user: 'authUser'
    }),

    created () {

      axios.get('/api/plans/' + this.$route.params.planId).then((response) => {
        let plan = response.data;

        Object.keys(plan).forEach((key) => {

          if (key === 'sections') {
            plan.sections.forEach((section, i) => {
              Object.keys(section).forEach((sectionKey) => {
                if (!this.form.sections[i]) {
                  this.form.sections[i] = {};
                }
                this.form.sections[i][sectionKey] = section[sectionKey]
              })
            })

          } else{
            this.form[key] = plan[key]
          }
        })

      })
    },

    methods: {
      async update () {
        const { data } = await this.form.put('/api/plans/' + this.form.id)
        console.log('updated:')
        console.log(this.form.data())

        this.$store.dispatch('updateUser', { user: data })
      },

      addSection() {
        this.form.sections.push({});
      }
    }
  }
</script>