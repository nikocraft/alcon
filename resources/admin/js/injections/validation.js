import Vue from 'vue'
import VeeValidate, { Validator } from 'vee-validate'

const isUniqueRoleName = (value) => {
    return axios.post(route('api.roles.unique'), { name: value }).then((response) => {
        // Notice that we return an object containing both a valid property and a data property.
        return {
            valid: response.data.valid,
            data: {
                message: response.data.message
            }
        }
    })
}

Validator.extend('unique_role_name', {
    validate: isUniqueRoleName,
    getMessage: (field, params, data) => {
        return data.message
    }
})

Vue.use(VeeValidate)
