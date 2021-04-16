$(function () {
  console.log('quiz start!');

  const quiz = document.getElementById('pole-dance-quiz');
  const button = document.getElementById('submit-reply');
  const checkboxs = document.querySelectorAll('.btn-check');

  const submit_replies = () => {
    const quizId = quiz.dataset.quizId;
    const checkboxs = document.querySelectorAll('.btn-check:checked');

    let replies = [];
    checkboxs.forEach(element => {
      replies.push([element.value, element.nextElementSibling.textContent]);
    });

    console.log(replies);
    
    $.ajax({
      url: "http://localhost:8000/quiz/" + quizId + "/send-replies",
      method: 'POST',
      dataType: 'json',
      data: JSON.stringify(replies),
      async: true,
      success: function (data, status) {
        console.log(status, data);
      },
      error: function (result, status, error) {
        console.error(result, status, error);
      },
      complete: function (result, status) {
        console.log(status, result);
        window.location.href = "http://localhost:8000/quiz/" + quizId + "/show_result";
      }
    });
  }

  const submit_reply = (event) => {
    const inner = quiz.firstElementChild;

    const nbrQuestions = parseInt(quiz.dataset.nbrQuestions, 10);
    let currentQuestion = parseInt(inner.dataset.currentQuestion, 10);

    
    if (currentQuestion < nbrQuestions) {
      inner.style.left = '-' + 100 * currentQuestion + '%';
      button.setAttribute('disabled', 'disabled');
      currentQuestion++;
      inner.dataset.currentQuestion = currentQuestion;
      document.querySelector('.current-question').textContent = currentQuestion;
    } else {
      submit_replies();
    }
  }

  button.setAttribute('disabled', 'disabled');

  [...checkboxs].forEach(checkbox => {
    checkbox.addEventListener('change', event => {
      if(event.currentTarget.checked) {
        button.removeAttribute('disabled');
      }
    })
  })

  button.addEventListener('click', submit_reply);
});

