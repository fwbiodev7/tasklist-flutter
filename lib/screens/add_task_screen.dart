import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import '../providers/task_provider.dart';

class AddTaskScreen extends StatefulWidget {
  const AddTaskScreen({super.key});

  @override
  State<AddTaskScreen> createState() => _AddTaskScreenState();
}

class _AddTaskScreenState extends State<AddTaskScreen> {
  final _titleController = TextEditingController();
  final _descController = TextEditingController();

  void _submitData() {
    final title = _titleController.text.trim();
    final desc = _descController.text.trim();

    if (title.isEmpty) return;

    Provider.of<TaskProvider>(context, listen: false).addTask(title, desc);

    _titleController.clear();
    _descController.clear();

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            const Icon(Icons.check_circle, color: Colors.black),
            const SizedBox(width: 10),
            const Text('Tarefa Adicionada!', style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold)),
          ],
        ),
        backgroundColor: const Color(0xFF00F0FF),
        duration: const Duration(seconds: 2),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'NOVA TAREFA',
          style: TextStyle(letterSpacing: 2, fontWeight: FontWeight.bold, color: Color(0xFF00F0FF)),
        ).animate().fade().slideY(begin: -0.2),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const SizedBox(height: 20),
            TextField(
              controller: _titleController,
              style: const TextStyle(color: Colors.white, fontSize: 18),
              decoration: InputDecoration(
                labelText: 'TÍTULO DA TAREFA',
                labelStyle: const TextStyle(color: Color(0xFF00F0FF), letterSpacing: 1),
                enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: const Color(0xFF00F0FF).withOpacity(0.3), width: 1.5),
                  borderRadius: BorderRadius.circular(16),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: const BorderSide(color: Color(0xFF00F0FF), width: 2.5),
                  borderRadius: BorderRadius.circular(16),
                ),
                filled: true,
                fillColor: const Color(0xFF161B29),
              ),
            ).animate().fade(delay: 100.ms).slideX(begin: -0.1),
            const SizedBox(height: 20),
            TextField(
              controller: _descController,
              maxLines: 4,
              style: const TextStyle(color: Colors.white, fontSize: 16),
              decoration: InputDecoration(
                labelText: 'DESCRIÇÃO (OPCIONAL)',
                labelStyle: const TextStyle(color: Color(0xFF00F0FF), letterSpacing: 1),
                enabledBorder: OutlineInputBorder(
                  borderSide: BorderSide(color: const Color(0xFF00F0FF).withOpacity(0.3), width: 1.5),
                  borderRadius: BorderRadius.circular(16),
                ),
                focusedBorder: OutlineInputBorder(
                  borderSide: const BorderSide(color: Color(0xFF00F0FF), width: 2.5),
                  borderRadius: BorderRadius.circular(16),
                ),
                filled: true,
                fillColor: const Color(0xFF161B29),
              ),
            ).animate().fade(delay: 200.ms).slideX(begin: -0.1),
            const SizedBox(height: 40),
            Container(
              decoration: BoxDecoration(
                boxShadow: [
                  BoxShadow(
                    color: const Color(0xFF00F0FF).withOpacity(0.3),
                    blurRadius: 20,
                    spreadRadius: 2,
                  )
                ],
                borderRadius: BorderRadius.circular(16),
              ),
              child: ElevatedButton(
                onPressed: _submitData,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF00F0FF),
                  foregroundColor: Colors.black,
                  padding: const EdgeInsets.symmetric(vertical: 18),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  elevation: 0,
                ),
                child: const Text(
                  'CRIAR TAREFA',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    letterSpacing: 2,
                  ),
                ),
              ),
            )
            .animate(onPlay: (controller) => controller.repeat(reverse: true))
            .shimmer(duration: 3000.ms, color: Colors.white.withOpacity(0.5))
            .animate() // Entrada
            .fade(delay: 300.ms)
            .slideY(begin: 0.2),
          ],
        ),
      ),
    );
  }
}
