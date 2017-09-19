<template>
  <card :title="$t('your_lesson_plans')">
    <div v-for="plan in plans" class="card">
      <div class="card-body">
        <h4 class="card-title">{{ plan.title }}</h4>
        <p class="card-text">{{ plan.description }}</p>
        <a href="#" class="btn btn-primary">Go somewhere</a>
      </div>
    </div>
  </card>
</template>

<script>
    import Form from 'vform'
    import { mapGetters } from 'vuex'
    import axios from 'axios'

    export default {
        data: () => ({
            form: new Form({
                name: '',
                email: ''
            }),
            plans: []
        }),

        computed: mapGetters({
            user: 'authUser'
        }),

        created () {
            // Fill the form with user data.
            this.form.keys().forEach(key => {
                this.form[key] = this.user[key]
            })

            axios.get('/api/plans').then((response) => {
              this.plans = response.data;
            })
        },

        methods: {

        }
    }
</script>
