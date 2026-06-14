import MobileController from './MobileController'
import ParamController from './ParamController'
import SunatController from './SunatController'

const Api = {
    MobileController: Object.assign(MobileController, MobileController),
    ParamController: Object.assign(ParamController, ParamController),
    SunatController: Object.assign(SunatController, SunatController),
}

export default Api