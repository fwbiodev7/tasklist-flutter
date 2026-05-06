import 'package:flutter/material.dart';
import '../models/task_model.dart';
import 'dart:math';

class TaskProvider with ChangeNotifier {
  final List<Task> _tasks = [];

  List<Task> get tasks => _tasks;

  List<Task> get pendingTasks => _tasks.where((t) => !t.isCompleted).toList();
  List<Task> get completedTasks => _tasks.where((t) => t.isCompleted).toList();

  void addTask(String title, String description) {
    final newTask = Task(
      id: DateTime.now().millisecondsSinceEpoch.toString() + Random().nextInt(1000).toString(),
      title: title,
      description: description,
    );
    _tasks.add(newTask);
    notifyListeners();
  }

  void toggleTaskCompletion(String id) {
    final taskIndex = _tasks.indexWhere((t) => t.id == id);
    if (taskIndex >= 0) {
      _tasks[taskIndex].toggleCompleted();
      notifyListeners();
    }
  }

  void deleteTask(String id) {
    _tasks.removeWhere((t) => t.id == id);
    notifyListeners();
  }
}
