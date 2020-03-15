import { format } from 'date-fns'

// Example of usage:
// @{{ testDate | localdate }}
// @{{ testDate | localdate('yyyy-MM') }}

export default function localdate (date, formatStr = 'yyyy-MM-dd HH:mm') {
    return format(new Date(`${date} UTC`), formatStr)
}
