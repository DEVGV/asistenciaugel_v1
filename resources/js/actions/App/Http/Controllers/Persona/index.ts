import TelefonoController from './TelefonoController'
import EmailController from './EmailController'
import DomicilioController from './DomicilioController'
import PersonaController from './PersonaController'

const Persona = {
    TelefonoController: Object.assign(TelefonoController, TelefonoController),
    EmailController: Object.assign(EmailController, EmailController),
    DomicilioController: Object.assign(DomicilioController, DomicilioController),
    PersonaController: Object.assign(PersonaController, PersonaController),
}

export default Persona