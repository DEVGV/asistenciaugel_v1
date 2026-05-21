import PersonaController from './PersonaController'
import TelefonoController from './TelefonoController'
import EmailController from './EmailController'
import DomicilioController from './DomicilioController'

const Persona = {
    PersonaController: Object.assign(PersonaController, PersonaController),
    TelefonoController: Object.assign(TelefonoController, TelefonoController),
    EmailController: Object.assign(EmailController, EmailController),
    DomicilioController: Object.assign(DomicilioController, DomicilioController),
}

export default Persona