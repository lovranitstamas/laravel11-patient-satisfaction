import Axios from 'axios';

export default {

  ExportFilteredResponses(selectedSurveyId) {
    return new Promise((resolve, reject) => {
      Axios.post('api/export/filteredResponses', {
        survey_id: selectedSurveyId
      })
        .then((response) => {
          // response.data.message =>  'message' => 'Exportálás megtörtént.'
          resolve(response.data);
        })
        .catch(function (err) {
          reject(err);
        });
    })
  },

}
