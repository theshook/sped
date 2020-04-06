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

                  <b-form-invalid-feedback id="invalid-input-email">
                    This field is required and must be a valil e-mail
                    address.
                  </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Phone" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.phone.$model"
                    :state="validateState('phone')"
                    aria-describedby="invalid-input-phone"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-phone">
                    This field is must be 7 digits for telephone number and 11
                    digits for mobile phone number.
                  </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Fax" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.fax.$model"
                    :state="validateState('fax')"
                    aria-describedby="invalid-input-fax"
                    description="This field is optional"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-fax">
                    This field is must be atleast 8
                    digits.
                  </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Address" label-class="text-sm">
                  <b-form-textarea
                    v-model="$v.form.address.$model"
                    :state="validateState('address')"
                    rows="4"
                    aria-describedby="invalid-input-address"
                  ></b-form-textarea>

                  <b-form-invalid-feedback id="invalid-input-address">This field is required.</b-form-invalid-feedback>
                </b-form-group>
              </b-col>

              <b-col lg="6">
                <p class="text-muted font-weight-bolder text-small">Visit checklist:</p>
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

                  <b-form-invalid-feedback id="invalid-input-date-completed">
                    This field is must be atleast 8
                    digits.
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>

              <b-col lg="6">
                <b-form-group label="Completed by" label-class="text-sm">
                  <b-form-input
                    v-model="$v.form.completed_by.$model"
                    :state="validateState('completed_by')"
                    aria-describedby="invalid-input-completed-by"
                  ></b-form-input>

                  <b-form-invalid-feedback id="invalid-input-completed-by">
                    This field is required and must be atleast 3
                    characters.
                  </b-form-invalid-feedback>
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

                  <b-form-invalid-feedback id="invalid-input-child-name">
                    This field is required and must be atleast 3
                    characters.
                  </b-form-invalid-feedback>
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

                  <b-form-invalid-feedback id="invalid-input-ucla-id">
                    This field is required and must contain
                    digit(s).
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-form-row>

            <b-form-group label="Best phone number to reach caregiver" label-class="text-sm">
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
              label="1.)  What are your main concerns about your child's development and/or behavior?"
              label-class="text-sm"
            >
              <b-form-textarea
                v-model="$v.form.q1.$model"
                :state="validateState('q1')"
                aria-describedby="invalid-input-q1"
                rows="3"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-q1">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="2.) What age was your child when you first became concerned about his/her development or behavior?"
              label-class="text-sm"
            >
              <b-form-textarea
                v-model="$v.form.q2.$model"
                :state="validateState('q2')"
                aria-describedby="invalid-input-q2"
                rows="3"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-q2">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="3.) What do you hope to gain from this visit?"
              label-class="text-sm"
            >
              <b-form-textarea
                v-model="$v.form.q3.$model"
                :state="validateState('q3')"
                aria-describedby="invalid-input-q3"
                rows="3"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-q3">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="4.) Describe your child's special qualities and strengths:"
              label-class="text-sm"
            >
              <b-form-textarea
                v-model="$v.form.q4.$model"
                :state="validateState('q4')"
                aria-describedby="invalid-input-q4"
                rows="3"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-q4">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="5.) Who referred you to the Developmental-Behavioral Pediatrics?"
              label-class="text-sm"
            >
              <b-form-select
                v-if="!q5_input_show"
                v-model="$v.form.q5.$model"
                :state="validateState('q5')"
                aria-describedby="invalid-input-q5"
              >
                <b-form-select-option value="I referred myself">I referred myself</b-form-select-option>
                <b-form-select-option value="None">None</b-form-select-option>
              </b-form-select>

              <b-form-input
                v-model="$v.form.q5.$model"
                :state="validateState('q5')"
                placeholder="Please type your answer here"
                v-if="q5_input_show"
              ></b-form-input>

              <b-form-checkbox v-model="q5_input_show" @change="toggleQ5input">
                <small class="text-muted">Other answer</small>
              </b-form-checkbox>

              <b-form-invalid-feedback id="invalid-input-q5">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="6.) Please list any other doctors that your child has seen within the last 3 years"
              label-class="text-sm"
            >
              <div class="mt-2" v-if="form.q6.length > 0">
                <b-table stiped hover borderless :items="form.q6"></b-table>
              </div>

              <div class="text-center mt-2">
                <b-button variant="primary" size="sm" class="rounded" v-b-modal.q6-modal>Add</b-button>
                <b-button variant="danger" size="sm" class="rounded">Clear</b-button>
              </div>
            </b-form-group>

            <b-form-group
              label="7.) Who is your child's primary pediatrician?"
              label-class="text-sm"
            >
              <b-form-textarea
                v-model="$v.form.q7.$model"
                :state="validateState('q7')"
                aria-describedby="invalid-input-q7"
                rows="3"
              ></b-form-textarea>

              <b-form-invalid-feedback id="invalid-input-q7">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="8.) Mother's age at time of birth" label-class="text-sm">
              <b-form-input
                type="number"
                maxlength="2"
                v-model="$v.form.q8.$model"
                :state="validateState('q8')"
                aria-describedby="invalid-input-q8"
              ></b-form-input>

              <b-form-invalid-feedback id="invalid-input-q8">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="9.) How many times has the biological mother been pregnant?"
              label-class="text-sm"
            >
              <b-form-input
                type="number"
                maxlength="2"
                v-model="$v.form.q9.$model"
                :state="validateState('q9')"
                aria-describedby="invalid-input-q9"
              ></b-form-input>

              <b-form-invalid-feedback id="invalid-input-q9">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="9-b.) How many resulted in a live birth?" label-class="text-sm">
              <b-form-input
                type="number"
                maxlength="2"
                v-model="form.q9b"
                aria-describedby="invalid-input-q9b"
              ></b-form-input>

              <b-form-invalid-feedback id="invalid-input-q9b">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group label="9-c.) How many resulted in a miscarriage?" label-class="text-sm">
              <b-form-input
                type="number"
                maxlength="2"
                v-model="form.q9b"
                aria-describedby="invalid-input-q9c"
              ></b-form-input>

              <b-form-invalid-feedback id="invalid-input-q9c">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="10.) Was this child the product of a multiple birth pregnancy?"
              label-class="text-sm"
            >
              <b-form-select
                v-model="q10_input_show"
                maxlength="2"
                aria-describedby="invalid-input-10"
              >
                <b-form-select-option value="true">Yes</b-form-select-option>
                <b-form-select-option value="false">No</b-form-select-option>
              </b-form-select>

              <div class="mt-2" v-if="q10_input_show">
                <small class="text-muted">Please specify what type</small>
                <b-form-input v-model="$v.form.q10.$model" :state="validateState('q10')"></b-form-input>
              </div>
              <b-form-invalid-feedback id="invalid-input-10">This field is required</b-form-invalid-feedback>
            </b-form-group>
          </section>

          <section v-if="page === 4">
            <b-form-group
              label="11.) Was reproductive technology or assistance used for this pregnancy?"
              label-class="text-sm"
            >
              <b-form-select
                v-model="q11_input_show"
                maxlength="2"
                aria-describedby="invalid-input-10"
              >
                <b-form-select-option value="true">Yes</b-form-select-option>
                <b-form-select-option value="false">No</b-form-select-option>
              </b-form-select>

              <div class="mt-2" v-if="q11_input_show">
                <small class="text-muted">Please specify what type</small>
                <b-form-input v-model="$v.form.q11.$model" :state="validateState('q11')"></b-form-input>
              </div>
              <b-form-invalid-feedback id="invalid-input-11">This field is required</b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="12.) Was reproductive technology or assistance used for this pregnancy?"
              label-class="text-sm"
            >
              <b-form-checkbox-group class="text-muted text-small mt-2" v-model="form.q12_arr">
                <b-form-checkbox value="High blood pressume">High blood pressume</b-form-checkbox>
                <b-form-checkbox value="Diabete">Diabete</b-form-checkbox>
                <b-form-checkbox value="Depression or mood swings">Depression or mood swings</b-form-checkbox>
                <b-form-checkbox value="Anxiety">Anxiety</b-form-checkbox>
                <b-form-checkbox value="Other" @change="toggleQ12input">Other</b-form-checkbox>
              </b-form-checkbox-group>

              <div class="mt-2" v-if="q12_input_show">
                <small class="text-muted">Please specify what type</small>
                <b-form-input v-model="$v.form.q12.$model" :state="validateState('q12')"></b-form-input>
              </div>
              <b-form-invalid-feedback id="invalid-input-12">This field is required</b-form-invalid-feedback>
            </b-form-group>
          </section>
        </b-form>
      </b-card-body>

      <b-card-footer class="text-right">
        <b-button variant="warning" class="text-white" @click="prev" v-if="page > 1">Prev</b-button>
        <b-button variant="primary" @click="next" v-if="page !== 0">Next</b-button>
      </b-card-footer>
    </b-card>

    <!-- MODALS -->

    <!-- QUESTION #6 SubForm inputs -->
    <b-modal
      id="q6-modal"
      title="Doctor Information"
      ok-title="Add"
      ok-variant="primary"
      @ok="q6addData"
      @hidden="q6resetData"
      button-size="sm"
    >
      <b-form-group label="Name" label-class="text-sm">
        <b-form-input
          v-model="$v.q6_arr.name.$model"
          :state="validateStateQ6('name')"
          aria-describedby="invalid-input-q6a"
        ></b-form-input>

        <b-form-invalid-feedback id="invalid-input-q6a">This field is required</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Role" label-class="text-sm">
        <b-form-input
          v-model="$v.q6_arr.role.$model"
          :state="validateStateQ6('role')"
          aria-describedby="invalid-input-q6b"
        ></b-form-input>

        <b-form-invalid-feedback id="invalid-input-q6b">This field is required</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group label="Contact" label-class="text-sm">
        <b-form-input
          type="number"
          max="11"
          v-model="$v.q6_arr.contact.$model"
          :state="validateStateQ6('contact')"
          aria-describedby="invalid-input-q6c"
        ></b-form-input>

        <b-form-invalid-feedback id="invalid-input-q6c">This field is required</b-form-invalid-feedback>
      </b-form-group>
    </b-modal>
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
      form_q6_fields: [
        {
          label: "Name"
        },
        {
          label: "Role"
        },
        {
          label: "Contact"
        }
      ],
      q12_options: [
        {
          text: "High Blood Pressure",
          value: "High Blood Pressure"
        },
        {
          text: "Diabetes",
          value: "Diabetes"
        },
        {
          text: "Depression or mood swings",
          value: "Depression or mood swings"
        },
        {
          text: "Anxiety",
          value: "Anxiety"
        },
        {
          text: "Other",
          value: "Other"
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
        caregiver_contact: null,

        //Intake form questions
        q1: null,
        q2: null,
        q3: null,
        q4: null,
        q5: null,
        q6: [],
        q7: null,
        q8: null,
        q9: null,
        q9b: null,
        q9c: null,
        q10: null,
        q11: null,
        q12: null,
        q12_arr: []
      },

      //Intake form inputs subdata (1 or more value)
      q6_arr: {
        name: null,
        role: null,
        contact: null
      },

      q5_input_show: false,
      q10_input_show: null,
      q11_input_show: null,
      q12_input_show: false,

      //Form page
      page: 4
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
      },

      //Intake form questions
      q1: {},
      q2: {},
      q3: {},
      q4: {},
      q5: {},
      q7: {
        required,
        minLength: minLength(3)
      },
      q8: {
        required,
        minLength: minLength(2),
        maxLength: maxLength(2)
      },
      q9: {
        required,
        minLength: minLength(1)
      },
      q10: {
        required,
        minLength: minLength(3)
      },

      q11: {
        minLength: minLength(3)
      },

      q12: {
        required,
        minLength: minLength(3)
      }
    },

    q6_arr: {
      name: { required },
      role: { required },
      contact: { required }
    }
  },
  computed: {},
  mounted() {},
  methods: {
    validateState: function(name) {
      const { $dirty, $error } = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    validateStateQ6: function(name) {
      const { $dirty, $error } = this.$v.q6_arr[name];
      return $dirty ? !$error : null;
    },

    next: function() {
      this.page++;
    },

    prev: function() {
      this.page--;
    },

    toggleQ5input: function() {
      this.q5_input_show = !this.q5_input_show;
    },

    q6addData: function(event) {
      this.$v.q6_arr.$touch();
      if (this.$v.q6_arr.$anyError) {
        return;
      } else {
        this.form.q6.push({
          name: this.q6_arr.name,
          role: this.q6_arr.role,
          contact: this.q6_arr.contact
        });
        this.$bvModal.hide("q6-modal");
        this.q6resetData();
      }
    },

    q6resetData: function() {
      this.q6_arr.name = null;
      this.q6_arr.role = null;
      this.q6_arr.contact = null;

      this.$v.q6_arr.$reset();
    },

    toggleQ12input: function() {
      this.q12_input_show = !this.q12_input_show;
      console.log(this.q12_input_show);
    }
  }
};
</script>
