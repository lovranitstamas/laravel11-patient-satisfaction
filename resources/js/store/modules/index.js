import surveyStore from "./Survey.store";
import questionnaireStore from "./Questionnaire.store";
import tableStore from "./Table.store";

const modules = {
  Table: tableStore,
  Survey: surveyStore,
  Questionnaire: questionnaireStore,
}

export default modules