<template>
  <card :title="[ plan.date, 'YYYY-MM-DD' ] | moment('dddd, MMMM Do YYYY')">
    <h1>{{ plan.title }}</h1>
    <router-link v-if="plan.user_id == user.id" :to="{ name: 'plans.edit', params: { planId: plan.id }}" class="btn btn-outline-primary btn-sm">
      <i class="fa fa-edit"></i> {{ $t('edit') }}
    </router-link>
    <a href="#" class="btn btn-outline-success btn-sm">
      <i class="fa fa-plus"></i> {{ $t('duplicate') }}
    </a>

    <p class="card-text" style="margin-top:15px">{{ plan.description }}</p>
    <div v-for="section in plan.sections" class="card">
      <div class="card-body">
        <h4 class="card-title">{{ section.event }}</h4>
        <span class="badge badge-info">{{ section.title }}</span>
        <p>
          <template v-for="contentLine in section.content.split('\n')">
            {{contentLine}} <br/>
          </template>
        </p>
      </div>
    </div>
  </card>
</template>

<script>
    import Form from 'vform'
    import { mapGetters } from 'vuex'
    import axios from 'axios'
    import moment from 'moment'

    export default {
        data: () => ({
            form: new Form({
                name: '',
                email: ''
            }),
            plan: {}
        }),

        computed: mapGetters({
            user: 'authUser'
        }),

        created () {
            // Fill the form with user data.
            this.form.keys().forEach(key => {
                this.form[key] = this.user[key]
            })


            axios.get('/api/plans/' + this.$route.params.planId).then((response) => {
              this.plan = response.data;
            })
        },

        methods: {

        }
    }
</script>
