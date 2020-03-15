export default {
    functional: true,
    render(h, context) {
        function getVisibility() {
            let value = context.props.value
            let dependency = context.props.dependency
            let output = true

            if(dependency) {
                if(dependency.if && value!=dependency.if) {
                    output = false
                }
                else if(!value) {
                    output = false
                }
                else if(dependency.condition) {
                    let conditionValue = dependency.value
                    let lacmus = value.includes(conditionValue)
                    output = !!lacmus
                }
            }
            return output
        }

        return getVisibility() ? context.slots().default[0] : false
    }
}
