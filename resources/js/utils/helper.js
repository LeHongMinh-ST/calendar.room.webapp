import {VALIDATE_EMAIL_REGEX, VALIDATE_PHONE_REGEX_RULE} from "./constants";
import moment from 'moment'

export const isValidEmail = (email) => {
    let result =  false
    if (email && typeof email === 'string') {
        const regex = RegExp(VALIDATE_EMAIL_REGEX);
        result = regex.test(email.trim())
    }
    return result
}

export const isValidPhone = (phone) => {
    let result = false

    if (phone && typeof phone === 'string') {
        let trimPhone = phone.trim()

        if (trimPhone) {
            const regexRule = RegExp(VALIDATE_PHONE_REGEX_RULE);

            let ruleMatchs = trimPhone.match(regexRule);

            if (ruleMatchs && ruleMatchs.length > 0) {
                result = (ruleMatchs[0] === trimPhone)
            }
        }
    }

    return result
}

export const formatDate = (date) => {
    return moment(date).format('HH:mm DD/MM/YYYY')
}

export const formatTime = (date) => {
    return moment(date).lang('vi').fromNow();
}