class Todo {
  constructor(containerId) {
    this.tasks = JSON.parse(localStorage.getItem("tasks")) || [];
    this.term = "";
    this.container = document.getElementById(containerId);

    document.addEventListener("click", (e) => {
      if (this.currentEditInput && e.target !== this.currentEditInput.input) {
        this.saveEdit();
      }
    });

    this.currentEditInput = null;
  }

  save() {
    localStorage.setItem("tasks", JSON.stringify(this.tasks));
  }

  get filteredTasks() {
    if (this.term.length < 2) return this.tasks;
    return this.tasks.filter(task =>
      task.text.toLowerCase().includes(this.term.toLowerCase())
    );
  }

  highlight(text) {
    if (this.term.length < 2) return text;
    const regex = new RegExp(this.term, "gi");
    return text.replace(regex, match => `<mark style="background-color:#bcfd94;color:black">${match}</mark>`);
  }

  draw() {
    this.container.innerHTML = "";

    this.filteredTasks.forEach((task, index) => {
      const div = document.createElement("div");
      div.classList.add("task-item");

      const spanText = document.createElement("span");
      spanText.classList.add("task-text");
      spanText.innerHTML = this.highlight(task.text);
      spanText.addEventListener("click", (e) => {
        e.stopPropagation();
        if (this.currentEditInput) this.saveEdit();

        const input = document.createElement("input");
        input.type = "text";
        input.value = task.text;
        input.classList.add("edit-input");
        spanText.replaceWith(input);
        input.focus();

        this.currentEditInput = { input, index, field: "text" };
      });

      const spanDate = document.createElement("span");
      spanDate.classList.add("task-date");
      spanDate.textContent = task.date || "";
      spanDate.addEventListener("click", (e) => {
        e.stopPropagation();
        if (this.currentEditInput) this.saveEdit();

        const input = document.createElement("input");
        input.type = "datetime-local";
        input.value = task.date || "";
        input.classList.add("edit-input");
        spanDate.replaceWith(input);
        input.focus();

        this.currentEditInput = { input, index, field: "date" };
      });

      const removeBtn = document.createElement("button");
      removeBtn.textContent = "Usuń";
      removeBtn.addEventListener("click", () => this.removeTask(index));

      div.appendChild(spanText);
      div.appendChild(document.createTextNode(" ")); // odstęp
      div.appendChild(spanDate);
      div.appendChild(document.createTextNode(" "));
      div.appendChild(removeBtn);

      this.container.appendChild(div);
    });
  }

  saveEdit() {
    if (!this.currentEditInput) return;
    const { input, index, field } = this.currentEditInput;
    const newValue = input.value.trim();

    if (field === "text") {
      if (newValue.length >= 3 && newValue.length <= 255) {
        this.tasks[index].text = newValue;
      }
    } else if (field === "date") {
      this.tasks[index].date = newValue; // dowolna data
    }

    this.currentEditInput = null;
    this.save();
    this.draw();
  }

  addTask(text, date) {
    text = text.trim();
    if (text.length < 3 || text.length > 255) return;

    this.tasks.push({ text, date });
    this.save();
    this.draw();
  }

  removeTask(index) {
    this.tasks.splice(index, 1);
    this.save();
    this.draw();
  }

  setSearchTerm(term) {
    this.term = term;
    this.draw();
  }
}

const todo = new Todo("list");

document.getElementById("addButton").addEventListener("click", () => {
  const text = document.getElementById("taskText").value;
  const date = document.getElementById("taskDate").value;
  todo.addTask(text, date);

  document.getElementById("taskText").value = "";
  document.getElementById("taskDate").value = "";
});

document.getElementById("search").addEventListener("input", (e) => {
  todo.setSearchTerm(e.target.value);
});

todo.draw();
