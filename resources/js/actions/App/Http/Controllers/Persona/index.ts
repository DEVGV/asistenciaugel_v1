import PersonaController from './PersonaController'
import PersonaMasivaController from './PersonaMasivaController'
import TelefonoController from './TelefonoController'
import EmailController from './EmailController'
import DomicilioController from './DomicilioController'

const Persona = {
    PersonaController: Object.assign(PersonaController, PersonaController),
    PersonaMasivaController: Object.assign(PersonaMasivaController, PersonaMasivaController),
    TelefonoController: Object.assign(TelefonoController, TelefonoController),
    EmailController: Object.assign(EmailController, EmailController),
    DomicilioController: Object.assign(DomicilioController, DomicilioController),
}

export default Persona