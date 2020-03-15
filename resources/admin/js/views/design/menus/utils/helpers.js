import Vue from 'vue'
import standardComponentsList from '../items/preparedList'

export function ucFirst(string = '') {
    return string.charAt(0).toUpperCase() + string.slice(1)
}

export function prepareValue(val, type) {
    let output

    if (type == 'boolean') {
        output = stringToBoolean(val)
    } else if (type == 'number') {
        output = Number(val)
    } else {
        output = val
    }

    return output
}

export function processSettings(rawSettings) {
    let processedSettings = {}

    for (var j = 0; j < rawSettings.length; j++) {
        let setting = rawSettings[j]
        let key = setting.key
        // let initProp = 'init' + key.charAt(0).toUpperCase() + key.slice(1)

        processedSettings[key] = prepareValue(setting.value, setting.type)
    }

    return processedSettings
}

export function getComponentByName(name, componentType = 'block', standardComponents = standardComponentsList) {
    console.log('inside get component by name')
    let globalComponents = Vue.options.components
    let component
    name = ucFirst(name)

    if (globalComponents[name]) component = globalComponents[name]
    else if (standardComponents[name]) component = standardComponents[name][componentType]

    return component
}

export function processSettingsConfig(name, standardComponents = standardComponentsList) {
    let globalComponents = Vue.options.components
    let component

    if (globalComponents[name+'Settings']) component = globalComponents[name+'Settings']
    else if (standardComponents[name]) component = standardComponents[name]['settings']

    return component
        ? component.customSettings || component.options.customSettings
        : undefined
}

export function getComponentsList(currentComponentName, componentsType = 'general') {
    let processedComponents
    let components

    switch (componentsType) {
        default:
            processedComponents = standardComponentsList
        break
    }

    let allowedList = getComponentSetting(currentComponentName, 'allowedList') || []
    if (allowedList.length) {
        processedComponents = collectComponents(processedComponents, allowedList)
    }

    let disallowedList = getComponentSetting(currentComponentName, 'disallowedList') || []
    if (disallowedList.length) {
        processedComponents = collectComponents(processedComponents, disallowedList, false)
    }

    components = _(processedComponents) //wrap object so that you can chain lodash methods
        .pickBy((o) => {
            if (o.parent && !currentComponentName) return false
            if (o.parent && currentComponentName && o.parent != currentComponentName.toLowerCase()) return false
            return true
        })
        .mapValues((o, key) => {
            let name = o.name || ucFirst(key)
            return {
                title: name,
                block: ucFirst(key)
            }
        })
        .values() //get the values of the result
        .value()

    return components
}

export function getComponentSetting(componentName, settingName) {
    let val = standardComponentsList.hasOwnProperty(componentName) && standardComponentsList[componentName][settingName]
    return val
}

export function collectComponents(source, list, allowed = true) {
    let preparedList = _.map(list, v => ucFirst(v))

    return allowed
        ? _.pick(source, preparedList)
        : _.omit(source, preparedList)
}

function stringToBoolean(string) {
    string = string || ''
    switch(string.toLowerCase().trim()){
        case "true": case "yes": case "1": return true;
        case "false": case "no": case "0": case null: return false;
        default: return Boolean(string);
    }
}

/**
 * Deep diff between two object, using lodash
 * @param  {Object} object Object compared
 * @param  {Object} base   Object to compare with
 * @return {Object}        Return a new object who represent the diff
 */
export function difference(object, base) {
    function changes(object, base) {
        return _.transform(object, function(result, value, key) {
            if (!_.isEqual(value, base[key].default)) {
                result[key] = (_.isObject(value) && _.isObject(base[key].default)) ? changes(value, base[key].default) : value;
            }
        });
    }
    return changes(object, base);
}

/**
 * Deep diff between component settings, using lodash
 * @param  {Object} object Object compared
 * @param  {Object} base   Object to compare with that holds default values of component
 * @return {Object}        Return a new object who represent the diff
 */
export function differenceSettings(object, base) {
    function changes(object, base) {
        return _.transform(object, function(result, value, key) {
            if (!_.isEqual(value, base[key]['default'])) {
                result[key] = (_.isObject(value) && _.isObject(base[key]['default'])) ? changes(value, base[key]['default']) : value;
            }
        });
    }
    return changes(object, base);
}
