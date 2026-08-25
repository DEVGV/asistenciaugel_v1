import telefonos from './telefonos'
import emails from './emails'
import domicilios from './domicilios'

const personas = {
    telefonos: Object.assign(telefonos, telefonos),
    emails: Object.assign(emails, emails),
    domicilios: Object.assign(domicilios, domicilios),
}

export default personas