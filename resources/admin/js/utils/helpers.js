import Vue from 'vue'
import standardComponentsList from '~/blocks/components/preparedList'

export function ucFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1)
}

export function prepareValue(val, type) {
    let output

    if(type == 'boolean') {
        output = typeof val === 'string' ? stringToBoolean(val) : val
    } else if(type == 'number') {
        output = Number(val)
    } else {
        output = val
    }

    return output
}

export function processSettings(rawSettings) {
    let processedSettings = {}

    for(var j = 0; j < rawSettings.length; j++) {
        let setting = rawSettings[j]
        let key = setting.key

        processedSettings[key] = prepareValue(setting.value, setting.type)
    }

    return processedSettings
}

export function getComponentByName(name, componentType = 'block', standardComponents = standardComponentsList) {
    let globalComponents = Vue.options.components
    let component
    let ucName = ucFirst(name)

    if(componentType == 'block' && globalComponents[name]) component = globalComponents[name]
    else if(componentType == 'settings' && globalComponents[name+'-settings']) component = globalComponents[name+'-settings']
    else if(standardComponents[ucName]) component = standardComponents[ucName][componentType]
    return component
}

export function processSettingsConfig(name, standardComponents = standardComponentsList) {
    let component = standardComponents[name] && standardComponents[name]['settings']

    return component
        ? component.customSettings || component.options.customSettings
        : undefined
}

export function getComponentsList(params = {}) {
    let {currentComponentName, componentsList, componentsType = 'general', location} = params
    let processedComponents
    let components

    switch (componentsType) {
        case 'tp':
            processedComponents = window.customComponents || {}
            components = _(processedComponents) //wrap object so that you can chain lodash methods
                .mapValues((o, key) => {
                    let name = o.name || ucFirst(key)
                    return {
                        title: name,
                        block: o.block && o.block.name
                    }
                })
                .values()
                .value()
            return components
        break
        default:
            processedComponents = standardComponentsList
        break
    }

    if(location) {
        processedComponents = _.pickBy(processedComponents, (o, k) => {
            return o.location ? o.location.indexOf(location)+1 : true
        })
    }
    if(currentComponentName) {
        let allowedList = getComponentSetting(currentComponentName, 'allowedList') || []
        if(allowedList.length) {
            processedComponents = collectComponents(processedComponents, allowedList)
        }

        let disallowedList = getComponentSetting(currentComponentName, 'disallowedList') || []
        if(disallowedList.length) {
            processedComponents = collectComponents(processedComponents, disallowedList, false)
        }
    }
    if(componentsList && _.isArray(componentsList)) {
        processedComponents = collectComponents(processedComponents, componentsList)
    }

    components = _(processedComponents) //wrap object so that you can chain lodash methods
        .pickBy((o) => {
            if(o.parent && !currentComponentName) return false
            if(o.parent && currentComponentName && o.parent != currentComponentName.toLowerCase()) return false
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
            if(!_.isEqual(value, base[key].default)) {
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
            if(!_.isEqual(value, base[key]['default'])) {
                result[key] = (_.isObject(value) && _.isObject(base[key]['default'])) ? changes(value, base[key]['default']) : value;
            }
        });
    }
    return changes(object, base);
}

export function moveElementInArray(array, value, positionChange) {
    let oldIndex = array.indexOf(value)
    if(oldIndex > -1) {
        let newIndex = (oldIndex + positionChange)

        if(newIndex < 0) {
          newIndex = 0
        } else if(newIndex >= array.length) {
          newIndex = array.length
        }

        let arrayClone = array.slice()
        arrayClone.splice(oldIndex,1)
        arrayClone.splice(newIndex,0,value)

        return arrayClone
    }
    return array
}

export const stateMerge = function(state, value, propName, ignoreNull) {
    if(
        Object.prototype.toString.call(value) === "[object Object]" &&
        (propName == null || state.hasOwnProperty(propName))
    ) {
        const o = propName == null ? state : state[propName];
        for(var prop in value) {
            stateMerge(o, value[prop], prop, ignoreNull);
        }
        return;
    }
    if(!ignoreNull || value !== null) Vue.set(state, propName, value);
}

export function setPropDeep(obj, props, value) {
    const prop = props.shift()
    if (!obj[prop]) {
      Vue.set(obj, prop, {})
    }
    if (!props.length) {
      if (value && typeof value === 'object' && !Array.isArray(value)) {
        obj[prop] = { ...obj[prop], ...value }
      } else {
        obj[prop] = value
      }
      return
    }
    setPropDeep(obj[prop], props, value)
}

// export setPropDeep

export function getPropDeep (obj, props) {
    const prop = props.shift()
    if (!obj[prop] || !props.length) {
      return obj[prop]
    }
    return getPropDeep(obj[prop], props)
  }

// export getPropDeep
