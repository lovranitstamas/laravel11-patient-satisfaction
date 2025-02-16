import tableStore from "./Table.store";
import surveyStore from "./Survey.store";
import questionnaireStore from "./Questionnaire.store";
import responseStore from "./Response.store";

const modules = {
  Table: tableStore,
  Survey: surveyStore,
  Questionnaire: questionnaireStore,
  Response: responseStore
}

export default modules