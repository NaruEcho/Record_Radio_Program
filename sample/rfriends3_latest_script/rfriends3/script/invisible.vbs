Dim p1
Set objs = CreateObject("Wscript.Shell")
SEt objArgs = WScript.Arguments

If objArgs.Count = 1 Then
  p = Wscript.Arguments(0)
ElseIf objArgs.Count = 2 Then
  p = Wscript.Arguments(0) & " " & Wscript.Arguments(1)
ElseIf objArgs.Count = 3 Then
  p = Wscript.Arguments(0) & " " & Wscript.Arguments(1) & " " & Wscript.Arguments(2)
End If
objs.Run "cmd /c " & p, 0, False
