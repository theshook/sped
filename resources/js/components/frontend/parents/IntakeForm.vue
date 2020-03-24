<template>
  <div>
    <b-card no-body>
      <b-card-body>
        <b-form>
          <section v-if="page == 1">
            <b-row>
              <b-col lg="6">
                <b-form-group label="Email" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.email.$model"
                    :state="validateState('email')"
                    aria-describedby="invalid-input-email"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-email"
                    >This field is required and must be a valil e-mail
                    address.</b-form-invalid-feedback
                  >
                </b-form-group>

                <b-form-group label="Phone" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.phone.$model"
                    :state="validateState('phone')"
                    aria-describedby="invalid-input-phone"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-phone"
                    >This field is must be 7 digits for telephone number and 11
                    digits for mobile phone number.</b-form-invalid-feedback
                  >
                </b-form-group>

                <b-form-group label="Fax" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.fax.$model"
                    :state="validateState('fax')"
                    aria-describedby="invalid-input-fax"
                    description="This field is optional"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-fax"
                    >This field is must be atleast 8
                    digits.</b-form-invalid-feedback
                  >
                </b-form-group>

                <b-form-group label="Address" label-class="text-sm">
                  <b-form-textarea
                    v-model="$v.form.address.$model"
                    :state="validateState('address')"
                    rows="4"
                    aria-describedby="invalid-input-address"
                  ></b-form-textarea>

                  <b-form-invalid-feedback id="invalid-input-address"
                    >This field is required.</b-form-invalid-feedback
                  >
                </b-form-group>
              </b-col>

              <b-col lg="6">
                <p class="text-muted font-weight-bolder text-small">
                  Visit checklist:
                </p>
                <b-form-group>
                  <b-form-checkbox-group
                    v-model="checklist_selected"
                    :options="checklist_options"
                    class="text-sm text-muted"
                    stacked
                  ></b-form-checkbox-group>
                </b-form-group>
              </b-col>
            </b-row>
          </section>

          <section v-if="page == 2">
            <b-form-row>
              <b-col lg="6">
                <b-form-group label="Date completed" label-class="text-sm">
                  <b-form-datepicker
                    v-model="$v.form.date_completed.$model"
                    :state="validateState('date_completed')"
                    aria-describedby="invalid-input-date-completed"
                  ></b-form-datepicker>

                  <b-form-invalid-feedback id="invalid-input-date-completed"
                    >This field is must be atleast 8
                    digits.</b-form-invalid-feedback
                  >
                </b-form-group>
              </b-col>

              <b-col lg="6">
                <b-form-group label="Completed by" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.completed_by.$model"
                    :state="validateState('completed_by')"
                    aria-describedby="invalid-input-completed-by"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-completed-by"
                    >This field is required and must be atleast 3
                    characters.</b-form-invalid-feedback
                  >
                </b-form-group>
              </b-col>
            </b-form-row>

            <b-form-row>
              <b-col lg="6">
                <b-form-group label="Child's name" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.child_name.$model"
                    :state="validateState('child_name')"
                    aria-describedby="invalid-input-child-name"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-child-name"
                    >This field is required and must be atleast 3
                    characters.</b-form-invalid-feedback
                  >
                </b-form-group>
              </b-col>

              <b-col lg="6">
                <b-form-group label="UCLA ID#" label-class="text-sm">
                  <b-form-input
                    type="number"
                    v-model="$v.form.ucla_id.$model"
                    :state="validateState('ucla_id')"
                    aria-describedby="invalid-input-ucla-id"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-ucla-id"
                    >This field is required and must contain
                    digit(s).</b-form-invalid-feedback
                  >
                </b-form-group>
              </b-col>
            </b-form-row>

            <b-form-group
              label="Best phone number to reach caregiver"
              label-class="text-sm"
            >
              <b-form-input
                type="number"
                v-model="$v.form.caregiver_contact.$model"
                :state="validateState('caregiver_contact')"
                aria-describedby="invalid-input-caregiver-contact"
              ></b-form-input>
            </b-form-group>
          </section>

          <section v-if="page == 3">
            <b-form-group
              label="Best phone number to reach caregiver"
              label-class="text-sm"
            >
              <b-form-textarea
                type="number"
                v-model="$v.form.q1.$model"
                :state="validateState('q1')"
                aria-describedby="invalid-input-q1"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-q1"
                >This field is required</b-form-invalid-feedback
              >
            </b-form-group>
          </section>
        </b-form>
      </b-card-body>

      <b-card-footer class="text-right">
        <b-button
          variant="warning"
          class="text-white"
          @click="prev"
          v-if="page > 1"
          >Prev</b-button
        >
        <b-button variant="primary" @click="next" v-if="page !== 0"
          >Next</b-button
        >
      </b-card-footer>
    </b-card>
  </div>
</template>

<script>
import {
  required,
  minLength,
  maxLength,
  email
} from "vuelidate/lib/validators";
export default {
  name: "IntakeForm",
  props: ["host"],
  data() {
    return {
      checklist_selected: [],
      checklist_options: [
        {
          text: "Intake Form",
          value: "Intake Form"
        },
        {
          text:
            "Teacher/Provider Questionnaire (Please copy the form if your child has multiple providers)",
          value:
            "Teacher/Provider Questionnaire (Please copy the form if your child has multiple providers)"
        },
        {
          text:
            "Most recent Regional Center Evaluation / Treatment Plan if applicable",
          value:
            "Most recent Regional Center Evaluation / Treatment Plan if applicable"
        },
        {
          text:
            "Reports (e.g. Speech-language, Occupational therapy, Psychological evaluation, Behavioral therapy) if applicable",
          value:
            "Reports (e.g. Speech-language, Occupational therapy, Psychological evaluation, Behavioral therapy) if applicable"
        },
        {
          text:
            "Most recent Individual Education Plan (IEP) AND any School Assessment Reports",
          value:
            "Most recent Individual Education Plan (IEP) AND any School Assessment Reports"
        },
        {
          text:
            "A recent video or DVD of your child is also useful in helping us to plan for your visit if you have one available",
          value:
            "A recent video or DVD of your child is also useful in helping us to plan for your visit if you have one available"
        }
      ],
      form: {
        email: null,
        phone: null,
        fax: null,
        address: null,
        date_completed: null,
        completed_by: null,
        child_name: null,
        ucla_id: null,
        caregiver_contact: null
      },

      page: 3
    };
  },
  validations: {
    form: {
      email: {
        email,
        required,
        minLenght: minLength(10),
        maxLength: maxLength(64)
      },
      phone: {
        required,
        minLenght: minLength(7),
        maxLength: maxLength(11)
      },
      fax: {
        required,
        minLenght: minLength(8),
        maxLength: maxLength(8)
      },
      address: {
        required,
        maxLength: maxLength(100)
      },
      date_completed: {
        required
      },
      completed_by: {
        required,
        minLenght: minLength(3),
        maxLength: maxLength(64)
      },
      child_name: {
        required,
        minLenght: minLength(3),
        maxLength: maxLength(64)
      },
      ucla_id: {
        required,
        minLenght: minLength(1),
        maxLength: maxLength(64)
      },
      caregiver_contact: {
        required,
        minLenght: minLength(7),
        maxLength: maxLength(11)
      }
    }
  },
  computed: {},
  mounted() {},
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    next: function() {
      this.page++;
    },

    prev: function() {
      this.page--;
    }
  }
};
</script>
